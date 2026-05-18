<?php

namespace App\Models;

use App\Models\User;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CaseCloseProgresses;
use App\Models\ExplorationTargetIdentity;


class ExplorationRencanaAksi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'exploration_rencana_aksi';
    protected $guarded = []; 
    protected $primaryKey = 'id_exploration_rencana_aksi';
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

    public function explorationTargetIdentities()
    {
        return $this->hasMany(ExplorationTargetIdentity::class, 'exploration_rencana_aksi_id', 'id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }

}
