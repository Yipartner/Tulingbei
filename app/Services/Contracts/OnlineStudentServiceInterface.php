<?php
/**
 * Created by PhpStorm.
 * User: andyhui
 * Date: 17-11-17
 * Time: 下午12:56
 */

namespace App\Services\Contracts;


interface OnlineStudentServiceInterface
{
    // 直接拿到数据层的方法，可以减少一些不必要的封装

    function getRepository();
}