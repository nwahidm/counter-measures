<?php

namespace App\Models\Observation;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseCloseProgresses;

class ObservCollectInfo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'observation_information_collection';
    protected $guarded = [];
    protected $casts = [
        'information_collection_date' => 'datetime:Y-m-d',
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

    public function sprint()
    {
        return $this->belongsTo(ObservDirective::class, 'surat_perintah_id', 'id');
    }
    public function threat()
    {
        return $this->hasMany(ObservThreat::class, 'information_collection_id', 'id');
    }
    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }
}
