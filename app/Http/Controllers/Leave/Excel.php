<?php
/**
 * Created by PhpStorm.
 * User: jiangbaiyan
 * Date: 2018-10-2
 * Time: 9:20
 */

namespace App\Http\Controllers\Leave;


use App\Exports\HolidayLeaveExport;
use App\Http\Config\ComConf;
use App\Util\Logger;
use Illuminate\Support\Facades\Validator;
use src\ApiHelper\ApiResponse;
use src\Exceptions\ParamValidateFailedException;

class Excel {

    /**
     * 导出节假日信息至excel
     * @return string
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
        $export = new HolidayLeaveExport($id);
        $path = "export_{$id}.xlsx";
        try{
            $export->store($path,'public');
        }catch (\Exception $e){
            Logger::fatal('leave|save_holiday_excel_failed|id:' . $id);
        }
        $path = ComConf::HOST . '/storage/' . $path;
        return ApiResponse::responseSuccess(['path' => $path]);
    }

}