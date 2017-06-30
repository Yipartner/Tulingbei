<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 17/6/30
 * Time: 上午12:02
 */

namespace App\Services\Contracts;


interface UserServiceInterface
{
    // 直接拿到数据层的方法，可以减少一些不必要的封装

    function getRepository();

    function register(array $userInfo):int;

    function active(int $userId):bool;

    function loginBy(string $param,string $identifier,string $password,string $ip):string;

    function login(int $userId,string $ip):string;

    function logout(int $userId);

    // 辅助类函数

    function isUserExist(array $condition):bool;
}