<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 17/6/28
 * Time: 下午9:54
 */

namespace App\Exceptions\Auth;


use App\Exceptions\BaseException;

class UserExistedException extends BaseException
{
    protected $code = 20003;
    protected $data = "User Existed";
}