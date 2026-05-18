<?php

namespace App\Models\Research;

use App\Models\MasterSatker;
use App\Models\OpenCase;
use App\Models\User;
use App\Models\CaseProgresses;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchSprint extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_surat_perintah';
    protected $guarded = [];
    protected $casts = [
        'tanggal_sprint' => 'datetime:Y-m-d',
        'tanggal_mulai_sprint' => 'datetime:Y-m-d',
        'tanggal_akhir_sprint' => 'datetime:Y-m-d',
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
        return $this->belongsTo(OpenCase::class, 'id_case', 'id');
    }

    public function researchLapinsus()
    {
        return $this->hasMany(ResearchLapinsus::class, 'id_sprint', 'id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }
}
