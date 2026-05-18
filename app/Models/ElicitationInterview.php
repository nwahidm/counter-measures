<?php

namespace App\Models;

use App\Models\User;
use App\Models\Documents;
use App\Models\VideoDocuments;
use App\Models\OpenCase;
use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CaseProgresses;

class ElicitationInterview extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'elicitation_hasil_wawancara';
    protected $guarded = []; 
    protected $primaryKey = 'id_elicitation_interview_result';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'interviewer_schedule' => 'date:Y-m-d'
    ];

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

    public function documents()
    {
        return $this->belongsTo(Documents::class, 'id_elicitation_interview_result', 'relation_id');
    }

    public function videoDocuments()
    {
        return $this->belongsTo(VideoDocuments::class, 'id_elicitation_interview_result', 'relation_id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }
    

    public function eliciAdfoll()
    {
        return $this->hasMany(ElicitationAdFoll::class, 'elicitation_hasil_wawancara_id', 'id_elicitation_interview_result');
    }


}
