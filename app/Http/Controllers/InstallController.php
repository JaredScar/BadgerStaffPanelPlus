<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use App\Models\Server;
use App\Models\Staff;
use App\Support\Installer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use PDO;
use Throwable;

class InstallController extends Controller
{
    public function show(string $step = 'welcome'): View|RedirectResponse
    {
        if (!in_array($step, Installer::STEPS, true)) {
            return redirect()->route('install.show', ['step' => 'welcome']);
        }

        $guard = $this->guardStep($step);
        if ($guard) {
            return $guard;
        }

        return view('install.' . $step, $this->viewData($step));
    }

    public function save(Request $request, string $step): RedirectResponse
    {
        return match ($step) {
            'welcome' => $this->saveWelcome(),
            'agreement' => $this->saveAgreement($request),
            'config' => $this->saveConfig($request),
            'database' => $this->saveDatabase($request),
            'admin' => $this->saveAdmin($request),
            'discord' => $this->saveDiscord($request),
            'complete' => $this->saveComplete(),
            default => redirect()->route('install.show', ['step' => 'welcome']),
        };
    }

    public function testDatabase(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $this->connectServer($validated);
            return response()->json([
                'ok' => true,
                'message' => 'Connected to the database server.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function saveWelcome(): RedirectResponse
    {
        $requirements = Installer::requirements();
        if (!$requirements['ok']) {
            return back()->withErrors([
                'requirements' => 'This server does not meet the requirements for BadgerStaffPanel+.',
            ]);
        }

        session(['install.welcome' => true]);
        return redirect()->route('install.show', ['step' => 'agreement']);
    }

    private function saveAgreement(Request $request): RedirectResponse
    {
        if (!$request->boolean('agree')) {
            return back()->withErrors([
                'agree' => 'You must accept the Terms of Service to continue.',
            ]);
        }

        session(['install.agreement' => true]);
        return redirect()->route('install.show', ['step' => 'config']);
    }

    private function saveConfig(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:80',
            'app_url' => 'required|url',
            'app_env' => 'required|in:local,production',
            'app_debug' => 'required|in:true,false',
            'use_captcha' => 'required|in:true,false',
            'public_bans' => 'required|in:true,false',
            'google_captcha_key' => 'nullable|string',
            'google_captcha_secret' => 'nullable|string',
            'master_api_key' => 'nullable|string',
        ]);

        if ($validated['use_captcha'] === 'true') {
            $request->validate([
                'google_captcha_key' => 'required|string',
                'google_captcha_secret' => 'required|string',
            ]);
        }

        $masterKey = $validated['master_api_key'] ?: bin2hex(random_bytes(32));

        Installer::setEnv([
            'APP_NAME' => $validated['app_name'],
            'APP_URL' => $validated['app_url'],
            'APP_ENV' => $validated['app_env'],
            'APP_DEBUG' => $validated['app_debug'],
            'USE_CAPTCHA' => $validated['use_captcha'],
            'PUBLIC_BANS' => $validated['public_bans'],
            'GOOGLE_CAPTCHA_KEY' => $validated['google_captcha_key'] ?? '',
            'GOOGLE_CAPTCHA_SECRET' => $validated['google_captcha_secret'] ?? '',
            'MASTER_API_KEY' => $masterKey,
            'APP_INSTALLED' => 'false',
        ]);

        $this->ensureAppKey();
        Artisan::call('config:clear');

        session([
            'install.config' => true,
            'install.app_name' => $validated['app_name'],
        ]);

        return redirect()->route('install.show', ['step' => 'database']);
    }

    private function saveDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $pdo = $this->connectServer($validated);
            $database = $validated['db_database'];
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '', $database) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

            Installer::setEnv([
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => $validated['db_host'],
                'DB_PORT' => $validated['db_port'],
                'DB_DATABASE' => $validated['db_database'],
                'DB_USERNAME' => $validated['db_username'],
                'DB_PASSWORD' => $validated['db_password'] ?? '',
            ]);

            Installer::applyDatabaseConfig([
                'connection' => 'mysql',
                'host' => $validated['db_host'],
                'port' => $validated['db_port'],
                'database' => $validated['db_database'],
                'username' => $validated['db_username'],
                'password' => $validated['db_password'] ?? '',
            ]);

            Artisan::call('config:clear');
            Artisan::call('migrate', ['--force' => true]);
        } catch (Throwable $e) {
            return back()->withInput()->withErrors([
                'database' => 'Database setup failed: ' . $e->getMessage(),
            ]);
        }

        session(['install.database' => true]);
        return redirect()->route('install.show', ['step' => 'admin']);
    }

    private function saveAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'server_name' => 'required|string|max:120',
            'server_slug' => 'required|string|max:64|regex:/^[a-z0-9-]+$/',
            'staff_username' => 'required|string|max:64',
            'staff_email' => 'required|email',
            'staff_password' => 'required|string|min:8|confirmed',
            'staff_discord' => 'nullable|numeric',
        ]);

        try {
            $server = Server::query()->first();
            if ($server) {
                $server->server_name = $validated['server_name'];
                $server->server_slug = $validated['server_slug'];
                $server->save();
            } else {
                $server = Server::create([
                    'server_name' => $validated['server_name'],
                    'server_slug' => $validated['server_slug'],
                    'webhook_enabled' => false,
                ]);
            }

            $staff = Staff::query()->where('staff_username', $validated['staff_username'])->first()
                ?: Staff::query()->orderBy('staff_id')->first();

            if (!$staff) {
                $staff = new Staff();
            }

            $staff->staff_username = $validated['staff_username'];
            $staff->staff_email = $validated['staff_email'];
            $staff->staff_discord = $validated['staff_discord'] ?: 0;
            $staff->password = Hash::make($validated['staff_password']);
            $staff->server_id = $server->server_id;
            if (Schema::hasColumn('staff', 'role')) {
                $staff->role = 'admin';
            }
            if (Schema::hasColumn('staff', 'status')) {
                $staff->status = 'active';
            }
            if (Schema::hasColumn('staff', 'join_date')) {
                $staff->join_date = now();
            }
            if (Schema::hasColumn('staff', 'notes')) {
                $staff->notes = 'Created by the web installer';
            }
            $staff->save();

            if (Schema::hasTable('staff_perms')) {
                DB::table('staff_perms')->where('staff_id', $staff->staff_id)->delete();
                foreach (['TOKEN_MANAGEMENT', 'STAFF_MANAGEMENT', 'SETTINGS_MANAGEMENT'] as $permission) {
                    DB::table('staff_perms')->insert([
                        'staff_id' => $staff->staff_id,
                        'permission' => $permission,
                        'allowed' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (Schema::hasTable('layouts') && !Layout::dashboardExists($staff->staff_id, 'main')) {
                Layout::createDefaultDashboard($staff->staff_id, $server->server_id, 'main');
            }
        } catch (Throwable $e) {
            return back()->withInput()->withErrors([
                'admin' => 'Could not create the admin account: ' . $e->getMessage(),
            ]);
        }

        session([
            'install.admin' => true,
            'install.admin_username' => $validated['staff_username'],
        ]);

        return redirect()->route('install.show', ['step' => 'discord']);
    }

    private function saveDiscord(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'use_discord' => 'required|in:true,false',
            'discord_client_id' => 'nullable|string',
            'discord_client_secret' => 'nullable|string',
            'discord_bot_token' => 'nullable|string',
            'discord_redirect_uri' => 'nullable|url',
            'discord_redirect_auth' => 'nullable|string',
            'master_admin_discord_id' => 'nullable|string',
            'master_admin_role_id' => 'nullable|string',
            'discord_webhook_url' => 'nullable|url',
        ]);

        if ($validated['use_discord'] === 'true') {
            $request->validate([
                'discord_client_id' => 'required|string',
                'discord_client_secret' => 'required|string',
                'discord_redirect_uri' => 'required|url',
            ]);
        }

        Installer::setEnv([
            'DISCORD_CLIENT_ID' => $validated['discord_client_id'] ?? '',
            'DISCORD_CLIENT_SECRET' => $validated['discord_client_secret'] ?? '',
            'DISCORD_BOT_TOKEN' => $validated['discord_bot_token'] ?? '',
            'DISCORD_REDIRECT_URI' => $validated['discord_redirect_uri'] ?? '',
            'DISCORD_REDIRECT_AUTH' => $validated['discord_redirect_auth'] ?? '',
            'MASTER_ADMIN_DISCORD_ID' => $validated['master_admin_discord_id'] ?? '',
            'MASTER_ADMIN_ROLE_ID' => $validated['master_admin_role_id'] ?? '',
        ]);

        if (!empty($validated['discord_webhook_url']) && Schema::hasTable('servers')) {
            $server = Server::query()->first();
            if ($server) {
                $server->discord_webhook_url = $validated['discord_webhook_url'];
                $server->webhook_enabled = true;
                $server->save();
            }
        }

        Artisan::call('config:clear');
        session(['install.discord' => true]);

        return redirect()->route('install.show', ['step' => 'complete']);
    }

    private function saveComplete(): RedirectResponse
    {
        $this->ensureAppKey();
        Installer::lock();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        session()->forget('install');

        if (Auth::guard('web')->check()) {
            return redirect()->route('DASHBOARD')->with('status', 'Installer settings saved.');
        }

        return redirect()->route('START')->with('status', 'Installation complete. Sign in with the admin account you created.');
    }

    private function viewData(string $step): array
    {
        $data = [
            'css_path' => 'installer',
            'view_name' => 'INSTALL',
            'customize' => false,
            'captcha' => false,
        ];

        return [
            'data' => $data,
            'step' => $step,
            'steps' => Installer::STEPS,
            'stepIndex' => Installer::currentStepIndex($step),
            'requirements' => Installer::requirements(),
            'tosContent' => $this->tosContent(),
            'defaults' => $this->formDefaults(),
            'alreadyInstalled' => Installer::isInstalled() || Installer::detectLegacyInstall(),
            'authenticated' => Auth::guard('web')->check(),
        ];
    }

    private function formDefaults(): array
    {
        return [
            'app_name' => env('APP_NAME', 'BadgerStaffPanel+'),
            'app_url' => env('APP_URL', url('/')),
            'app_env' => env('APP_ENV', 'local'),
            'app_debug' => env('APP_DEBUG', true) ? 'true' : 'false',
            'use_captcha' => env('USE_CAPTCHA', false) ? 'true' : 'false',
            'public_bans' => env('PUBLIC_BANS', true) ? 'true' : 'false',
            'google_captcha_key' => env('GOOGLE_CAPTCHA_KEY', ''),
            'google_captcha_secret' => env('GOOGLE_CAPTCHA_SECRET', ''),
            'master_api_key' => env('MASTER_API_KEY', ''),
            'db_host' => env('DB_HOST', '127.0.0.1'),
            'db_port' => env('DB_PORT', '3306'),
            'db_database' => env('DB_DATABASE', 'staffpanel_db'),
            'db_username' => env('DB_USERNAME', 'root'),
            'db_password' => env('DB_PASSWORD', ''),
            'server_name' => 'My FiveM Server',
            'server_slug' => 'my-server',
            'staff_username' => 'admin',
            'staff_email' => '',
            'staff_discord' => env('MASTER_ADMIN_DISCORD_ID', ''),
            'use_discord' => env('DISCORD_CLIENT_ID') ? 'true' : 'false',
            'discord_client_id' => env('DISCORD_CLIENT_ID', ''),
            'discord_client_secret' => env('DISCORD_CLIENT_SECRET', ''),
            'discord_bot_token' => env('DISCORD_BOT_TOKEN', ''),
            'discord_redirect_uri' => env('DISCORD_REDIRECT_URI', rtrim(env('APP_URL', url('/')), '/') . '/web'),
            'discord_redirect_auth' => env('DISCORD_REDIRECT_AUTH', ''),
            'master_admin_discord_id' => env('MASTER_ADMIN_DISCORD_ID', ''),
            'master_admin_role_id' => env('MASTER_ADMIN_ROLE_ID', ''),
            'discord_webhook_url' => '',
        ];
    }

    private function tosContent(): string
    {
        $path = public_path('tos/tos.txt');
        return is_file($path) ? File::get($path) : 'Terms of Service file is missing.';
    }

    private function guardStep(string $step): ?RedirectResponse
    {
        $required = [
            'agreement' => 'welcome',
            'config' => 'agreement',
            'database' => 'config',
            'admin' => 'database',
            'discord' => 'admin',
            'complete' => 'discord',
        ];

        if (Installer::isInstalled() || Installer::detectLegacyInstall() || Auth::guard('web')->check()) {
            return null;
        }

        $needed = $required[$step] ?? null;
        if ($needed && !session('install.' . $needed)) {
            return redirect()->route('install.show', ['step' => $needed]);
        }

        return null;
    }

    private function connectServer(array $db): PDO
    {
        $dsn = sprintf('mysql:host=%s;port=%s', $db['db_host'], $db['db_port']);
        return new PDO($dsn, $db['db_username'], $db['db_password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
    }

    private function ensureAppKey(): void
    {
        $key = env('APP_KEY');
        if (is_string($key) && str_starts_with($key, 'base64:')) {
            return;
        }

        Artisan::call('key:generate', ['--force' => true]);
    }
}
