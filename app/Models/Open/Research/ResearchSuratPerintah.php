<?php

namespace App\Models\Open\Research;

use App\Models\OpenCase;
use App\Models\User;
use App\Models\MasterSatker;
use App\Models\CaseEventHistoricalUpdates;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseProgresses;

class ResearchSuratPerintah extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_surat_perintah';
    protected $guarded = [];
    protected $casts = [
        'surat_perintah_date' => 'datetime:Y-m-d',
        'surat_perintah_date_started' => 'datetime:Y-m-d',
        'surat_perintah_date_finished' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_surat_perintah';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }

    public function researchSuratPerintahMember()
    {
        return $this->hasMany(ResearchSuratPerintahMember::class, 'surat_perintah_id', 'id_surat_perintah');
    }

    public function researchLaporanInformasiKhusus()
    {
        return $this->hasMany(ResearchLaporanInformasiKhusus::class, 'surat_perintah_id', 'id_surat_perintah')->withTrashed();
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'kode_satker');
    }

    public function caseEventHistoricalUpdates()
    {
        return $this->hasMany(CaseEventHistoricalUpdates::class, 'case_id', 'case_id');
    }

    
}
