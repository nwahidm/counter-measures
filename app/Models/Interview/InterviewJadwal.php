<?php

namespace App\Models\Interview;

use App\Models\MasterSatker;
use App\Models\OpenCase;
use App\Models\User;
use App\Models\CaseProgresses;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewJadwal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'interview_jadwal';
    protected $guarded = [];
    protected $casts = [
        'interviewer_schedule' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_interview_scheduler';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'kode_satker');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }

    public function interviewHasil()
    {
        return $this->hasMany(InterviewHasil::class, 'interview_scheduler_id', 'id_interview_scheduler')->withTrashed();
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }
}
