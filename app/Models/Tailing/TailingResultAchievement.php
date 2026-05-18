<?php

namespace App\Models\Tailing;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\User;
use App\Models\Documents;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseCloseProgresses;

class TailingResultAchievement extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tailing_hasil_yang_dicapai';
    protected $guarded = [];
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
        return $this->belongsTo(MasterSatker::class, 'kode_satker', 'kode_satker');
    }

    public function case()
    {
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }

     public function TailingPemahamanPerilaku()
    {
        return $this->belongsTo(TailingPemahamanPerilaku::class, 'tailing_pemahaman_perilaku_id', 'id');
    }
      
      public function TailingTargetOperasi()
    {
        return $this->belongsTo(TailingTargetOperasi::class, 'tailing_target_operasi_id', 'id');
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
