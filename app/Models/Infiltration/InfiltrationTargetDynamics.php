<?php

namespace App\Models\Infiltration;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationResultAchievement;
use App\Models\CaseCloseProgresses;

class InfiltrationTargetDynamics extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'infiltration_dinamika_target';
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
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'kode_satker');
    }

    public function case()
    {
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }

    public function secret_operation()
    {
        return $this->belongsTo(InfiltrationSecretOperation::class, 'infiltration_operasi_rahasia_id', 'id');
    }


    // public function sprint()
    // {
    //     return $this->belongsTo(ResearchSprint::class, 'id_sprint', 'id');
    // }
    
    public function infiltrationResultAchievements()
    {
        return $this->hasMany(InfiltrationResultAchievement::class, 'infiltration_dinamika_target_id', 'id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }
}
