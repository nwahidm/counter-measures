<?php

namespace App\Models;

use App\Models\User;
use App\Models\OpenCase;
use App\Models\MasterSatker;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationInterview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElicitationResult extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'elicitation_hasil_yang_dicapai';
    protected $guarded = []; 
    protected $primaryKey = 'id_elicitation_result';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
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

    public function elinterview()
    {
        return $this->belongsTo(ElicitationInterview::class, 'elicitation_hasil_wawancara_id', 'id_elicitation_interview_result');
    }

    public function elinadfoll()
    {
        return $this->belongsTo(ElicitationAdFoll::class, 'elicitation_advice_and_follow_up_id', 'id_elicitation_saran_dan_tindak_lanjut');
    }
}
