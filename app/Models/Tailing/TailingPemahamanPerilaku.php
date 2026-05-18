<?php

namespace App\Models\Tailing;

use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\User;
use App\Models\VideoDocuments;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\CaseCloseProgresses;

class TailingPemahamanPerilaku extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tailing_pemahaman_perilaku';
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
    
    public function tailingTargetOperations()
    {
        return $this->hasMany(TailingTargetOperasi::class, 'tailing_pemahaman_perilaku_id', 'id');
    }

    public function VideoDocuments()
    {
        return $this->belongsTo(VideoDocuments::class, 'id', 'relation_id');
    }
    public function caseProgress()
    {
        return $this->belongsTo(CaseCloseProgresses::class, 'case_id', 'case_id');
    }

}
