<?php

namespace App\Http\Middleware;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class verifyuser 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   
    public function handle(Request $request, Closure $next)
    {
        //checking if user is authenticated then can do actions otherwise will returned back
        $token = $request->header('token');
        if($token!=null)
        {
            $id=Str::substr($token, 60);
            $user = User::where([
                ['token', $token],
                ['id', $id]
            ])->first();
            if($user!=null){
                $request->userid=$id;
                return $next($request);
            }
            else{
                return response()->json([ 'message' => 'User is not authenticated,does not exist!'],404);
            }
        }
        else{
            return response()->json([ 'message' => 'Token is null, User should be authorized or login'],404);
        }
        
    }
   
}
