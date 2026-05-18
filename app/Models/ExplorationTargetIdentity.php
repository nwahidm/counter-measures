<?php

namespace App\Models;

use App\Models\User;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ExplorationResultAchievment;
use App\Models\ExplorationRencanaAksi;
use App\Models\CaseCloseProgresses;

class ExplorationTargetIdentity extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'exploration_target_identitas';
    protected $guarded = []; 
    protected $primaryKey = 'id_exploration_target_identity';
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
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }

    public function explorationResultAchievements()
    {
        return $this->hasMany(ExplorationResultAchievment::class, 'exploration_target_identity_id', 'id');
    }

    public function explorationRencanaAksi()
    {
        return $this->hasOne(ExplorationRencanaAksi::class, 'id_exploration_rencana_aksi', 'exploration_rencana_aksi_id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }
}
