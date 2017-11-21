<?php

namespace App\Http\Controllers;
use App\Exceptions\Common\FormValidationException;
use Illuminate\Http\Request;
use App\Common\Utils;
use App\Common\ValidationHelper;
use App\Repository\Models\Student;
use App\Services\StudentService;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class StudentController extends Controller
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function addStudent(Request $request)
    {
        //校内
        $inrules = [
            'name' => 'required|min:2|max:50',
            'stunum' => 'required',
            'mobile' => 'required|max:20',
            'faculty' => 'required|max:45',
            'major' => 'required|max:50',
            'sex' => 'required|max:10',
            'email' => 'required|max:45',
            'grade' => 'required|max:10',
            'class' =>'required',
            'lanqiaobei'=>'required',
            'school_type'=>'required',
            'school'=>'required'
        ];
        //校外
        $outrules=[
            'name' => 'required|min:2|max:50',
            'mobile' => 'required|max:20',
            'sex' => 'required|max:10',
            'faculty' => 'required|max:45',
            'major' => 'required|max:50',
            'email' => 'email|max:45',
            'grade' => 'required|max:10',
            'school' => 'required',
            'school_type'=>'required',
            'language'=>'required',
        ];
        if($request->school_type=='本校')
        {

            ValidationHelper::validateCheck($request->all(), $inrules);
            $studentInfo = ValidationHelper::getInputData($request, $inrules);
            $studentInfo['school']='东北大学秦皇岛分校';
        }
        else
        {
            ValidationHelper::validateCheck($request->all(), $outrules);
            $studentInfo = ValidationHelper::getInputData($request, $outrules);
            $studentInfo['school_type']='外校';
            $studentInfo['lanqiaobei']='否';
            $studentInfo['class']='';
            $studentInfo['stunum']='';
        }

        if($this->studentService->saveToDatabase($studentInfo)){
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
        $students = Student::all();
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

        $total_count = $this->studentService->getTotalCount();

        if (!empty($total_count))
            $data = $this->studentService->getStudents($page, $size);
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
            $total_count = $this->studentService->getTotalCount();
            if (!empty($total_count))
                $data = Student::all();//$this->studentService->getStudents(1,$total_count);
            else
                $data = null;
            $title = ['id',
                'name',
                'stunum',
                'school_type',
                'school',
                'mobile',
                'faculty',
                'major',
                'sex',
                'email',
                'grade',
                'class',
                'lanqiaobei'
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
