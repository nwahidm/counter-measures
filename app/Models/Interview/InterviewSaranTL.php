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

class InterviewSaranTL extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'interview_saran_dan_tindak_lanjut';
    protected $guarded = [];
    protected $casts = [
        'saran_dan_tindak_lanjut_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_interview_advice_and_follow_up';

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
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }

    public function interviewHasil()
    {
        return $this->belongsTo(InterviewHasil::class, 'interview_result_id', 'id_interview_result')->withTrashed();
    }

    public function interviewJadwal()
    {
        return $this->belongsTo(InterviewJadwal::class, 'interview_schedule_id', 'id_interview_scheduler');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }
}
