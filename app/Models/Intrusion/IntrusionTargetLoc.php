<?php

namespace App\Models\Intrusion;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseCloseProgresses;

class IntrusionTargetLoc extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'intrusion_target_lokasi';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
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
    public function environment()
    {
        return $this->hasMany(IntrusionTargetEnv::class, 'intrusion_target_location_id', 'id');
    }
    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }

}
