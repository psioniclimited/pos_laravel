<?php

namespace Modules\MobileAPI\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use JWTAuth;
use Modules\User\Entities\TenantUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Config;

class MobileLoginController extends Controller
{
    public function login(Request $request)
    {
//        $response = [
//            'token' =>
//                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjAuMTAyOjgwMDAvdXNlci9sb2dpbiIsImlhdCI6MTU2MDA3MjQzMywiZXhwIjoxNTYwMTE1NjMzLCJuYmYiOjE1NjAwNzI0MzMsImp0aSI6IlFXdkJKT1MyNTA2UkthOFUifQ.dXwA0j4fki2mfEIS4bCCPBTwldpR2lnBi15uSbGulRA'
//        ];
//        return response()->json($response);
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        $credentials['active'] = 1;

        try {
            Config::set('jwt.ttl', 365 * 24 * 60);
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
}
