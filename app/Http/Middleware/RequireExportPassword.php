<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards /export with the EXPORT_PASSWORD from .env (browser password prompt; any username).
 * Without a password set the export does not exist (404), so it is never left open by accident.
 */
class RequireExportPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $password = config('symphony.export.password');

        abort_if(blank($password), 404);

        if (! hash_equals((string) $password, (string) $request->getPassword())) {
            return response('Enter the export password to continue.', 401, [
                'WWW-Authenticate' => 'Basic realm="Export", charset="UTF-8"',
            ]);
        }

        return $next($request);
    }
}
