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

use App\Repository\Eloquent\StudentRepository;
use App\Services\Contracts\StudentServiceInterface;

class StudentService implements StudentServiceInterface
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getRepository()
    {
        return $this->studentRepository;
    }
    public function saveToDatabase(array $studentInfo): int
    {
        $uniques = [
            'mobile'
        ];

        foreach ($uniques as $unique) {
            if ($this->studentRepository->getBy($unique, $studentInfo[$unique])->count() >= 1) {

                $student = $this->studentRepository->getBy($unique, $studentInfo[$unique])->first();
                $this->studentRepository->update($studentInfo,$student -> id);
                return 0;
                //throw new UserExistedException($unique);
            }
        }
        $this->studentRepository->insert($studentInfo);
        return 1;
    }
    function getTotalCount()
    {
        return $this->studentRepository->getWhereCount();
    }
    function getStudents(int $page,int $size)
    {
        return $this->studentRepository->paginate($page,$size);
    }
    public function isStudentExist(array $condition): bool
    {
        return $this->studentRepository->getWhereCount($condition) == 1;
    }
}