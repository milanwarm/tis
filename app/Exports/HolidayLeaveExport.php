<?php

namespace App\Exports;

use App\Http\Model\Common\User;
use App\Http\Model\Leave\HolidayLeave;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HolidayLeaveExport implements FromQuery,ShouldAutoSize,WithHeadings
{

    use Exportable;

    private $holidayLeaveModel;

    public function __construct(int $holidayLeaveModelId) {
        $this->holidayLeaveModel = $holidayLeaveModelId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \src\Exceptions\UnAuthorizedException
     */
    public function query() {
        $userId = User::getUser(true);
       return HolidayLeave::query()->join('student', 'student.id', '=', 'holiday_leave.student_id')
           ->select('student.uid', 'student.name', 'student.class', 'holiday_leave.destination', 'holiday_leave.updated_at')
           ->where('holiday_leave.holiday_leave_model_id', $this->holidayLeaveModel)//是这个模板
           ->where('student.teacher_id', $userId)//自己的学生
           ->orderByDesc('holiday_leave.created_at');
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            '学号',
            '姓名',
            '班级',
            '目的地',
            '登记时间'
        ];
    }
}
