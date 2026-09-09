<?php

namespace App\Http\Middleware;

use App\Support\Installer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $isInstallRoute = $request->routeIs('install.*') || $request->is('web/install*');
        $hasInstall = Installer::isInstalled() || Installer::detectLegacyInstall();

        if ($hasInstall && !Installer::isInstalled()) {
            try {
                Installer::lock();
            } catch (\Throwable $e) {
                // Lock is best-effort for existing databases.
            }
        }

        if ($isInstallRoute) {
            if ($hasInstall && !Auth::guard('web')->check()) {
                return redirect()->guest(route('START'));
            }

            return $next($request);
        }

        if ($hasInstall) {
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
