<?php

namespace App\Http\Middleware;

use App\Support\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $isInstallRoute = $request->routeIs('install.*') || $request->is('web/install*');

        if (Installer::isInstalled()) {
            if ($isInstallRoute) {
                return redirect()->route('START');
            }

            return $next($request);
        }

        $installInProgress = $request->hasSession() && $request->session()->has('install.welcome');

        if (Installer::detectLegacyInstall() && !$installInProgress) {
            try {
                Installer::lock();
            } catch (\Throwable $e) {
                // Lock is best-effort for existing databases.
            }

            if ($isInstallRoute) {
                return redirect()->route('START');
            }

            return $next($request);
        }

        if ($isInstallRoute) {
            return $next($request);
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'BadgerStaffPanel+ is not installed yet.',
            ], 503);
        }

        return redirect()->route('install.show', ['step' => 'welcome']);
    }
}
