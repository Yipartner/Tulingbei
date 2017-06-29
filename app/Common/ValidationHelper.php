<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 17/6/29
 * Time: 下午11:24
 */

namespace App\Common;


use Illuminate\Http\Request;

/**
 * 表单验证辅助类
 * Class ValidationHelper
 * @package App\Common
 */

class ValidationHelper
{
    public static function validateCheck(array $inputs,array $rules)
    {
        $validator = Validator::make($inputs,$rules);

        if ($validator->fails()) {
            throw new FormValidatorException($validator->getMessageBag()->all());
        }
    }

    public static function getInputData(Request $request,array $rules)
    {
        $data = [];

        foreach ($rules as $key => $rule) {
            $data[$key] = $request->input($key,null);
        }

        return $data;
    }
}