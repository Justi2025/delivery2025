<?php
 

namespace App\Http\Middleware;

use App\Pomoschniki\PomoshToken;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;


class ApiAuthMiddleware
{

    public function __construct(
        private readonly PomoshToken $jwt
    )
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || !$this->isValidToken($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    private function isValidToken($token): bool
    {
        try {
            $this->jwt->decodirovat($token, env('JWT_SECRET'));
        }
        catch (Exception $e) {
            Log::log(LogLevel::DEBUG, $e);
            return false;
        }

        return true;
    }
}
