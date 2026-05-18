<?php

namespace App\Models\Open\Research;

use App\Models\User;
use App\Models\OpenCase;
use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseProgresses;

class ResearchLaporanInformasiKhusus extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_laporan_informasi_khusus';
    protected $guarded = [];
    protected $casts = [
        'tanggal_surat' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    // protected $primaryKey = 'id_laporan_informasi_khusus';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function researchSuratPerintah()
    {
        return $this->belongsTo(ResearchSuratPerintah::class, 'surat_perintah_id', 'id_surat_perintah');
    }

    public function researchSaranTindakLanjut()
    {
        return $this->hasMany(ResearchSaranTindakLanjut::class, 'laporan_informasi_khusus_id', 'id');
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'kode_satker');
    }
}
