<?php

namespace App\Http\Controllers;

use App\Common\ValidationHelper;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'mobile' => 'required|mobile|max:45',
            'password' => 'required|min:6|max:20'
        ];

        ValidationHelper::validateCheck($request->all(),$rules);

        $userInfo = ValidationHelper::getInputData($request,$rules);

        // todo 添加相关激活逻辑

        $userId = $this->userService->register($userInfo);

        return response()->json([
            'code' => 0,
            'data' => [
                'userId' => $userId
            ]
        ]);
    }

    public function login()
    {

    }
}
