<?php
/**
 * Created by PhpStorm.
 * User: andyhui
 * Date: 17-11-17
 * Time: 下午12:59
 */
namespace App\Services;



use App\Exceptions\Auth\PasswordWrongException;
use App\Exceptions\Auth\UserExistedException;
use App\Exceptions\Auth\UserNotExistException;

use App\Repository\Eloquent\OnlineStudentRepository;
use App\Repository\Eloquent\StudentRepository;
use App\Services\Contracts\OnlineStudentServiceInterface;
use App\Services\Contracts\StudentServiceInterface;

class OnlineStudentService implements OnlineStudentServiceInterface
{
    private $onlineStudentRepository;

    public function __construct(OnlineStudentRepository $onlineStudentRepository)
    {
        $this->onlineStudentRepository = $onlineStudentRepository;
    }

    public function getRepository()
    {
        return $this->onlineStudentRepository;
    }
    public function saveToDatabase(array $studentInfo): int
    {
        $uniques = [
            'mobile'
        ];

        foreach ($uniques as $unique) {
            if ($this->onlineStudentRepository->getBy($unique, $studentInfo[$unique])->count() >= 1) {

                $student = $this->onlineStudentRepository->getBy($unique, $studentInfo[$unique])->first();
                $this->onlineStudentRepository->update($studentInfo,$student -> id);
                return 0;
                //throw new UserExistedException($unique);
            }
        }
        $this->onlineStudentRepository->insert($studentInfo);
        return 1;
    }
    function getTotalCount()
    {
        return $this->onlineStudentRepository->getWhereCount();
    }
    function getStudents(int $page,int $size)
    {
        return $this->onlineStudentRepository->paginate($page,$size);
    }
    public function isStudentExist(array $condition): bool
    {
        return $this->onlineStudentRepository->getWhereCount($condition) == 1;
    }
}