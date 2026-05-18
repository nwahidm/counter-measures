<?php

namespace App\Models\Infiltration;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Documents;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CaseCloseProgresses;

class InfiltrationResultAchievement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'infiltration_hasil_yang_dicapai';
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
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }

    public function case()
    {
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }

      public function InfiltrationSecretOperation()
    {
        return $this->belongsTo(InfiltrationSecretOperation::class, 'infiltration_operasi_rahasia_id', 'id');
    }
      
      public function InfiltrationTargetDynamics()
    {
        return $this->belongsTo(InfiltrationTargetDynamics::class, 'infiltration_dinamika_target_id', 'id');
    }

    public function Documents()
    {
        return $this->belongsTo(Documents::class, 'id', 'relation_id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }
    

}
