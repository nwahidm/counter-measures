<?php

namespace App\Models;



use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;

use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationScenarioRelation;

use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;
use App\Models\ExplorationResultAchievment;

use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Tailing\TailingResultAchievement;

use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Infiltration\InfiltrationResultAchievement;

use App\Models\Intrusion\IntrusionTargetLoc;
use App\Models\Intrusion\IntrusionTargetEnv;
use App\Models\Intrusion\intrusionResult;

use App\Models\Tapping\TappingElectronicDevice;
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Tapping\TappingResultAchievement;


use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;




class CloseCase extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'close_case';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'case_date' => 'date:Y-m-d',
    ];

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }
    public function observationDirective()
    {
        return $this->hasMany(ObservDirective::class, 'case_id', 'id');
    }
    public function observationCollectInfo()
    {
        return $this->hasMany(ObservCollectInfo::class, 'case_id', 'id');
    }
    public function observationThreat()
    {
        return $this->hasMany(ObservThreat::class, 'case_id', 'id');
    }
    public function observationConnect()
    {
        return $this->hasMany(ObservConnect::class, 'case_id', 'id');
    }

     // Definisikan relasi dengan DelineationInformationVerification
     public function delineationInformationVerifications()
     {
         return $this->hasMany(DelineationInformationVerification::class, 'case_id', 'id');
     }
     public function delineationInformationValidations()
     {
         return $this->hasMany(DelineationInformationValidation::class, 'case_id', 'id');
     }
     public function delineationScenarioRelations()
     {
         return $this->hasMany(DelineationScenarioRelation::class, 'case_id', 'id');
     }


    public function explorationRencanaAksi()
    {
        return $this->hasMany(ExplorationRencanaAksi::class, 'case_id', 'id');
    }
    public function explorationTargetIdentity()
    {
        return $this->hasMany(ExplorationTargetIdentity::class, 'case_id', 'id');
    }
    public function explorationResultAchievement()
    {
        return $this->hasMany(ExplorationResultAchievment::class, 'case_id', 'id');
    }

    public function tailingPemahamanPerilaku()
    {
        return $this->hasMany(TailingPemahamanPerilaku::class, 'case_id', 'id');
    }
    public function tailingTargetOperasi()
    {
        return $this->hasMany(TailingTargetOperasi::class, 'case_id', 'id');
    }
    public function tailingResultAchievement()
    {
        return $this->hasMany(TailingResultAchievement::class, 'case_id', 'id');
    }


 
    // Definisikan relasi dengan InfiltrationSecretOperation
    public function infiltrationSecretOperations()
    {
        return $this->hasMany(InfiltrationSecretOperation::class, 'case_id', 'id');
    }
    public function infiltrationTargetDynamics()
    {
        return $this->hasMany(InfiltrationTargetDynamics::class, 'case_id', 'id');
    }
    public function infiltrationResulAchievement()
    {
        return $this->hasMany(InfiltrationResultAchievement::class, 'case_id', 'id');
    }


    public function intrusionLocation()
    {
        return $this->hasMany(IntrusionTargetLoc::class, 'case_id', 'id');
    }

    public function intrusionEnv()
    {
        return $this->hasMany(IntrusionTargetEnv::class, 'case_id', 'id');
    }

    public function intrusionResult()
    {
        return $this->hasMany(IntrusionResult::class, 'case_id', 'id');
    }



    public function location()
    {
        return $this->hasMany(IntrusionTargetLoc::class, 'case_id', 'id');
    }

    public function progress()
    {
        return $this->hasOne(CaseCloseProgresses::class, 'case_id', 'id');
    }

    // Definisikan relasi dengan CaseCloseProgresses
    public function caseCloseProgress()
    {
        return $this->hasOne(CaseCloseProgresses::class, 'case_id', 'id');
    }

    // Definisikan relasi dengan CaseCloseEventHistoricalUpdates
    public function caseCloseEventHistoricalUpdate()
    {
        return $this->hasOne(CaseCloseEventHistoricalUpdates::class, 'case_id', 'id');
    }

    public function tappingElectronicDevice()
    {
        return $this->hasMany(TappingElectronicDevice::class, 'case_id', 'id');
    }

    public function tappingIntelligentSignal()
    {
        return $this->hasMany(TappingIntelligentSignal::class, 'case_id', 'id');
    }

    public function tappingResultAchievement()
    {
        return $this->hasMany(TappingResultAchievement::class, 'case_id', 'id');
    }

    

    // Definisikan relasi dengan ExplorationRencanaAksi
    
    // Definisikan relasi dengan TailingPemahamanPerilaku
    
    // Definisikan relasi dengan TappingElectronicDevice
    
}
