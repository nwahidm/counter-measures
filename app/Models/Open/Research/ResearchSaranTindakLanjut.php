<?php

namespace App\Models\Open\Research;

use App\Models\User;
use App\Models\OpenCase;
use App\Models\Open\Research\ResearchSuratPerintah;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CaseProgresses;

class ResearchSaranTindakLanjut extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_saran_dan_tindak_lanjut';
    protected $guarded = [];
    protected $casts = [
        'saran_dan_tindak_lanjut_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_saran_dan_tindak_lanjut';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function researchLaporanInformasiKhusus()
    {
        return $this->belongsTo(ResearchLaporanInformasiKhusus::class, 'laporan_informasi_khusus_id', 'id')->withTrashed();
    }

    public function researchPotensiAght()
    {
        return $this->hasMany(ResearchPotensiAght::class, 'id_saran_tl', 'id_saran_dan_tindak_lanjut')->withTrashed();
    }

    public function caseProgress()
    {
        return $this->belongsTo(CaseProgresses::class, 'case_id', 'case_id');
    }


    public function researchSuratPerintah()
    {
        return $this->belongsTo(ResearchSuratPerintah::class, 'surat_perintah_id', 'id_surat_perintah');
    }

    public function case()
    {
        return $this->belongsTo(OpenCase::class, 'case_id', 'id');
    }
}
