<?php

namespace App\Http\Controllers;
use App\Exceptions\Common\FormValidationException;
use App\Repository\Models\OnlineStudents;
use App\Services\OnlineStudentService;
use Illuminate\Http\Request;
use App\Common\Utils;
use App\Common\ValidationHelper;
use App\Repository\Models\Student;
use App\Services\StudentService;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class OnlineStudentController extends Controller
{
    private $onlineStudentService;

    public function __construct(OnlineStudentService $onlineStudentService)
    {
        $this->onlineStudentService = $onlineStudentService;
    }

    public function addStudent(Request $request)
    {

        $rules=[
            'name' => 'required|min:2|max:50',
            'mobile' => 'required|max:20',
            'email' => 'email|max:45',
            'school' => 'required',
        ];

            ValidationHelper::validateCheck($request->all(), $rules);
            $studentInfo = ValidationHelper::getInputData($request, $rules);


        if($this->onlineStudentService->saveToDatabase($studentInfo)){
            $status = "insert success" ;
            return response()->json([
                'code' => 0,
                'data' => [
                    'status' => $status,
                    'type' => "insert"
                ]
            ]);
        } else {
            $status = "update success" ;
            return response()->json([
                'code' => 0,
                'data' => [
                    'status' => $status,
                    'type' => "update"
                ]
            ]);
        }


    }

    public function showStudent()
    {
        $students = OnlineStudents::all();
        return response()->json([
                'code' => 0,
                'data' => $students
            ]
        );
    }
    public function showStudentByPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'size' => 'integer|min:1|max:100'
        ]);

        if ($validator->fails())
            throw new FormValidationException($validator->getMessageBag()->all());

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $total_count = $this->onlineStudentService->getTotalCount();

        if (!empty($total_count))
            $data = $this->onlineStudentService->getStudents($page, $size);
        else
            $data = null;

        return response()->json([
            'code' => 0,
            'data' => [
                '$data' => $data,
                '$total_count' => $total_count
            ]
        ]);
    }
    public function exportStudent(Request $request)
    {

        if ($request->password!="neuqAcm111")
        {
            return response()->json([
                'code'=>'10000',
                'message'=>'密码错误'
            ]);
        }
        else {
            $total_count = $this->onlineStudentService->getTotalCount();
            if (!empty($total_count))
                $data = OnlineStudents::all();//$this->studentService->getStudents(1,$total_count);
            else
                $data = null;
            $title = ['id',
                'name',
                'mobile',
                'email',
            ];
            $cellData[] = $title;
            foreach ($data as $student) {
                $rowdata = [];
                foreach ($title as $key) {
                    $rowdata[] = $student[$key];
                }
                $cellData[] = $rowdata;
            }
            Excel::create('报名表', function ($excel) use ($cellData) {
                $excel->sheet('score', function ($sheet) use ($cellData) {
                    $sheet->rows($cellData);
                });
            })->export('xls');
        }
//        return response()->json([
//            'code' => 0,
//            'status' => 'success'
//        ]);
    }

}
