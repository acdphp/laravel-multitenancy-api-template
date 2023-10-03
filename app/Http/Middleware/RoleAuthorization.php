<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$authorizedRoleNames): Response
    {
        // Always add super admin to authorized roles
        $authorizedRoles = array_merge(
            Role::fromNamesLower($authorizedRoleNames),
            [Role::SUPER_ADMIN]
        );

        /**
         * @var User $user
         */
        $user = $request->user();

        if (! in_array($user->role, $authorizedRoles, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
