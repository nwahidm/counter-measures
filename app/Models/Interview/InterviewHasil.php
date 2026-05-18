<?php

namespace App\Models\Interview;

use App\Models\MasterSatker;
use App\Models\OpenCase;
use App\Models\User;
use App\Models\Documents;
use App\Models\VideoDocuments;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewHasil extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'interview_hasil';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_interview_result';

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

    public function documents()
    {
        return $this->belongsTo(Documents::class, 'id_interview_result', 'relation_id');
    }

    public function videoDocuments()
    {
        return $this->belongsTo(VideoDocuments::class, 'id_interview_result', 'relation_id');
    }

    public function interviewJadwal()
    {
        return $this->belongsTo(InterviewJadwal::class, 'interview_scheduler_id', 'id_interview_scheduler')->withTrashed();
    }

    public function interviewSaranTL()
    {
        return $this->hasMany(InterviewSaranTL::class, 'interview_result_id', 'id_interview_result')->withTrashed();
    }
}
