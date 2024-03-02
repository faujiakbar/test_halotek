<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AuthMiddleware
{

    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = $request->header('authorization');

        try {
            if(!$auth) throw new \Exception("Unauthorized to access API",401);
            $tmp = explode(" ", $auth);
            if(!isset($tmp[1])) throw new \Exception("Token problem",401);

            list($pre,$token) = $tmp;
            // validate JWT token
            $user = auth()->setToken($token)->user();
            if(!$user) throw new \Exception("Unauthorized",401);

            // merge request data
            $request->merge(['user' => $user]);
        } catch(\Exception $e){
            return Response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'code' => $e->getCode()
            ],$e->getCode());
        }

        return $next($request);
    }
}
