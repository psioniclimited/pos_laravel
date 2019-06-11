<?php

namespace Modules\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use JWTAuth;
use Modules\User\Entities\TenantUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthenticationController extends Controller
{
    /**
     * Login users.
     * @return Response
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        $credentials['active'] = 1;
//        dd($credentials);
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }


        $user = TenantUser::where('email', $request->email)->first();
        if ((new Carbon)->gt($user->company->valid_to))
            return response()->json(['error' => 'Subscription Expired'], 401);

        // all good so return the token
        return response()->json(compact('token'));
    }

    // somewhere in your controller
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        try {
            JWTAuth::invalidate($token);
            return response()->json(['success' => true, 'message' => "You have successfully logged out."]);
        } catch (TokenExpiredException $e) {
            return response()->json(['success' => true, 'message' => "Token Already Expired."]);
        } catch (TokenInvalidException $e) {
            return response()->json(['success' => true, 'message' => "Token Invalid."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
}
