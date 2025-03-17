<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'gender', 'dob', 'joining_date',
        'department_id', 'sub_department_id', 'shift_hours', 'annual_leave_balance', 'sick_leave_balance'
    ];

    protected $dates = ['deleted_at'];

    public function leaves()
    {
        return $this->hasOne(Leave::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class);
    }

}
