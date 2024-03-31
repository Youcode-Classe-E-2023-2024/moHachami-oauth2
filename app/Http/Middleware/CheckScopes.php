<?php




namespace App\Http\Middleware;

use Closure;

class CheckScopes
{
    public function handle($request, Closure $next, ...$scopes)
    {
        foreach ($scopes as $scope) {
            if (!$request->user()->tokenCan($scope)) {
            abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
