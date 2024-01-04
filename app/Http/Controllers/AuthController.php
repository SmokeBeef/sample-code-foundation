<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(AdminRequest $req)
    {
        try {
            $payload = $req->validated();



            $token = auth()->attempt([
                "admin_username" => $payload["admin_username"],
                "password" => $payload["admin_password"],
            ]);
            if (!$token) {
                return $this->responseError("username or password not match", 401);
            }


            $tokenExpires = auth()->factory()->getTTL() * 60;

            return $this->responseSuccess("success login", ["token" => $token, "expiresIn" => $tokenExpires, "type" => "Bearer"]);
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("There is Error in Server");
        }
    }

    public function refreshToken()
    {
        try {

            $token = auth()->refresh();

            return $this->responseSuccess("success get refresh token", ["token" => $token]);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function logout()
    {
        try {

            auth()->logout();

            return $this->responseSuccess("success logout");
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

}
