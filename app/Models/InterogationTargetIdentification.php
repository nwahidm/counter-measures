<?php

namespace App\Models;

use App\Models\OpenCase;
use App\Models\InterogationRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\InterogationResultAchievement;
use App\Models\CaseProgresses;
use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterogationTargetIdentification extends Model
{
    
    use HasFactory, HasUuids;

    protected $table = 'interrogation_identifikasi_target';
    protected $guarded = []; 
    protected $primaryKey = 'id_interogation_target_identification';
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

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }
    
    public function interogrecord()
    {
        return $this->belongsTo(InterogationRecord::class, 'interogation_record_id', 'id_interogation_record');
    }

    public function interogachievement()
    {
        return $this->hasMany(InterogationResultAchievement::class, 'interogation_target_identification_id', 'id_interogation_target_identification');
    }
}
