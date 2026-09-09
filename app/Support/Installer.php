<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Installer
{
    public const STEPS = [
        'welcome',
        'agreement',
        'config',
        'database',
        'admin',
        'discord',
        'complete',
    ];

    public static function lockPath(): string
    {
        return storage_path('app/installed');
    }

    public static function isInstalled(): bool
    {
        if (is_file(self::lockPath())) {
            return true;
        }

        return filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOLEAN);
    }

    public static function detectLegacyInstall(): bool
    {
        try {
            return Schema::hasTable('staff') && DB::table('staff')->exists();
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function lock(): void
    {
        $dir = dirname(self::lockPath());
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents(self::lockPath(), now()->toIso8601String() . PHP_EOL);
        self::setEnv(['APP_INSTALLED' => 'true']);
    }

    public static function currentStepIndex(?string $step): int
    {
        $index = array_search($step, self::STEPS, true);
        return $index === false ? 0 : $index;
    }

    public static function nextStep(string $step): ?string
    {
        $index = self::currentStepIndex($step);
        return self::STEPS[$index + 1] ?? null;
    }

    public static function previousStep(string $step): ?string
    {
        $index = self::currentStepIndex($step);
        return $index > 0 ? self::STEPS[$index - 1] : null;
    }

    public static function requiredExtensions(): array
    {
        return [
            'ctype',
            'curl',
            'dom',
            'fileinfo',
            'filter',
            'hash',
            'mbstring',
            'openssl',
            'pcre',
            'pdo',
            'pdo_mysql',
            'session',
            'tokenizer',
            'xml',
        ];
    }

    public static function requirements(): array
    {
        $extensions = [];
        foreach (self::requiredExtensions() as $extension) {
            $extensions[$extension] = extension_loaded($extension);
        }

        $php = version_compare(PHP_VERSION, '8.1.0', '>=');
        $laravel = version_compare(app()->version(), '10.0.0', '>=');
        $writable = [
            'storage' => is_writable(storage_path()),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env' => is_writable(base_path('.env')) || is_writable(base_path()),
        ];

        return [
            'php' => $php,
            'php_version' => PHP_VERSION,
            'laravel' => $laravel,
            'laravel_version' => app()->version(),
            'extensions' => $extensions,
            'writable' => $writable,
            'ok' => $php && $laravel && !in_array(false, $extensions, true) && !in_array(false, $writable, true),
        ];
    }

    public static function setEnv(array $values): void
    {
        $path = base_path('.env');
        if (!is_file($path)) {
            copy(base_path('.env.example'), $path);
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException('Unable to read .env');
        }

        foreach ($values as $key => $value) {
            $formatted = self::formatEnvValue($value);
            $pattern = '/^' . preg_quote((string) $key, '/') . '=.*$/m';
            $line = $key . '=' . $formatted;
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content, 1);
            } else {
                $content = rtrim($content) . PHP_EOL . $line . PHP_EOL;
            }
        }

        file_put_contents($path, $content);
    }

    public static function formatEnvValue(mixed $value): string
    {
        $value = $value === null ? '' : (string) $value;
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\s#"\'\\\\]/', $value)) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
        }

        return $value;
    }

    public static function applyDatabaseConfig(array $db): void
    {
        config([
            'database.default' => $db['connection'] ?? 'mysql',
            'database.connections.mysql.host' => $db['host'],
            'database.connections.mysql.port' => $db['port'],
            'database.connections.mysql.database' => $db['database'],
            'database.connections.mysql.username' => $db['username'],
            'database.connections.mysql.password' => $db['password'],
        ]);

        DB::purge('mysql');
    }
}
