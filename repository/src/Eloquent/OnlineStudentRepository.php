<?php
/**
 * Created by PhpStorm.
 * User: andyhui
 * Date: 17-11-17
 * Time: 上午11:50
 */
namespace App\Repository\Eloquent;

class OnlineStudentRepository extends AbstractRepository
{
    function model()
    {
        return 'App\Repository\Models\OnlineStudents';
    }
}