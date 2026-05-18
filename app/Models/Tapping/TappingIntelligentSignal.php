<?php

namespace App\Models\Tapping;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseCloseProgresses;

class TappingIntelligentSignal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tapping_intelligent_signal';
    protected $guarded = [];
    protected $casts = [
        'tanggal_penyadapan' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_tapping_intelligent_signal';

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

    public function tappingElectronicDevice()
    {
        return $this->belongsTo(TappingElectronicDevice::class, 'tapping_electronic_device_data_id', 'id_tapping_electronic_device')->withTrashed();
    }

    public function tappingResultAchievement()
    {
        return $this->hasMany(TappingResultAchievement::class, 'tapping_intelligent_signal_data_id', 'id_tapping_intelligent_signal')->withTrashed();
    }
    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }
}
