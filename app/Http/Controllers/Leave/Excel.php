<?php
/**
 * Created by PhpStorm.
 * User: jiangbaiyan
 * Date: 2018-10-2
 * Time: 9:20
 */

namespace App\Http\Controllers\Leave;


use App\Exports\HolidayLeaveExport;
use Illuminate\Support\Facades\Validator;
use src\Exceptions\ParamValidateFailedException;

class Excel {

    /**
     * 导出节假日信息至excel
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws ParamValidateFailedException
     */
    public function exportHolidayLeave(){
        $validator = Validator::make($params = \Request::all(),[
            'id' => 'required'
        ]);
        if ($validator->fails()){
            throw new ParamValidateFailedException($validator);
        }
        $id = $params['id'];
        return (new HolidayLeaveExport($id))->download('节假日信息.xlsx');
    }

}