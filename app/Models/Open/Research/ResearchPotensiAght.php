<?php

namespace App\Models\Open\Research;

use App\Models\User;
use App\Models\OpenCase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseProgresses;

class ResearchPotensiAght extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_potensi_aght';
    protected $guarded = [];
    protected $casts = [
        'waktu' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    // protected $primaryKey = 'id_potensi_aght';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function researchSaranTindakLanjut()
    {
        return $this->belongsTo(ResearchSaranTindakLanjut::class, 'id_saran_tl', 'id_saran_dan_tindak_lanjut');
    }

    public function researchLaporanInformasiKhusus()
    {
        return $this->belongsTo(ResearchLaporanInformasiKhusus::class, 'id_lapinsus', 'id');
    }

    public function researchSuratPerintah()
    {
        return $this->belongsTo(ResearchSuratPerintah::class, 'id_sprint', 'id_surat_perintah');
    }

    public function researchPotensiAghtLampiran()
    {
        return $this->hasMany(ResearchPotensiAghtLampiran::class, 'id_potensi_aght', 'id')->withTrashed();
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }
}
