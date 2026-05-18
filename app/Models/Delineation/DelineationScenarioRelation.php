<?php

namespace App\Models\Delineation;


use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Observation\ObservCollectInfo;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\CaseCloseProgresses;

class DelineationScenarioRelation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'delineation_scenario_relation';
    protected $guarded = [];
    protected $casts = [
        // 'tanggal_surat' => 'datetime:Y-m-d',
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
        return $this->belongsTo(MasterSatker::class, 'kode_satker', 'kode_satker');
    }

    public function case()
    {
        return $this->belongsTo(CloseCase::class, 'id_case', 'id');
    }

    public function observation_information_collection()
    {
        return $this->belongsTo(ObservCollectInfo::class, 'information_collection_id', 'id');
    }

    public function information_verification()
    {
        return $this->belongsTo(DelineationInformationVerification::class, 'information_verification_id', 'id');
    }

    public function information_validation()
    {
        return $this->belongsTo(DelineationInformationValidation::class, 'information_validation_id', 'id');
    }
    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }

    
}
