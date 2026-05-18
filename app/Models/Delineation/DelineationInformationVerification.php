<?php

namespace App\Models\Delineation;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\Observation\ObservCollectInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\CaseCloseProgresses;

class DelineationInformationVerification extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'delineation_information_verification';
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
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }

    public function observation_information_collection()
    {
        return $this->belongsTo(ObservCollectInfo::class, 'information_collection_id', 'id');
    }

     // Definisikan relasi dengan DelineationInformationValidation
     public function delineationInformationValidations()
     {
         return $this->hasMany(DelineationInformationValidation::class, 'information_verification_id', 'id');
     }

    // public function sprint()
    // {
    //     return $this->belongsTo(ResearchSprint::class, 'id_sprint', 'id');
    // }
}
