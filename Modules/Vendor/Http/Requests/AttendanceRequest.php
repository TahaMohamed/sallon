<?php

namespace Modules\Vendor\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Vendor\Models\Attendance;

class AttendanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d H:i',
            'reason' => 'nullable|string|max:200',
            'status' => 'required|in:' . join(',', Attendance::CASES),
            'employee_id' => ['required', function($attr, $value, $fail){
                $employee = User::query()
                    ->when($this->attendance,
                        fn($q) => $q->whereHas('attendances',
                            fn($q) => $q->where(['date' => now()
                            , 'status' => $this->status])
                        ))
                    ->where(['users.id' => $value, 'user_type' => User::EMPLOYEE])
                    ->whereRelation('employeeCenter','centers.user_id',auth()->id())
                    ->doesntExist();

                if ($employee){
                    $fail(__('dashboard.attendance.validation.employee_dosnt_exists'));
                }
            }]
        ];
    }
}
