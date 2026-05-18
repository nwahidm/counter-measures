<?php

namespace App\Models;

use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewHasil;
use App\Models\Interview\InterviewSaranTL;

use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchPotensiAght;


use App\Models\Research\ResearchSprint;
use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpenCase extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'open_case';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'tanggal_kasus' => 'date:Y-m-d',
        //'foto' => 'array'
    ];

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'id_satker', 'id_satker');
    }

    public function researchSprint()
    {
        return $this->hasMany(ResearchSprint::class, 'id_case', 'id');
    }

    public function researchSuratPerintah()
    {
        return $this->hasMany(ResearchSuratPerintah::class, 'case_id', 'id')->withTrashed();
    }

    public function researchLaporanInformasiKhusus()
    {
        return $this->hasMany(ResearchLaporanInformasiKhusus::class, 'case_id', 'id');
    }

    public function researchSaranTindakLanjut()
    {
        return $this->hasMany(ResearchSaranTindakLanjut::class, 'case_id', 'id');
    }

    public function researchPotensiAght()
    {
        return $this->hasMany(ResearchPotensiAght::class, 'case_id', 'id');
    }


    
    public function interviewJadwal()
    {
        return $this->hasMany(InterviewJadwal::class, 'case_id', 'id');
    }

    public function interviewHasil()
    {
        return $this->hasMany(InterviewHasil::class, 'case_id', 'id');
    }

    public function interviewSaranTL()
    {
        return $this->hasMany(InterviewSaranTL::class, 'case_id', 'id');
    }



    public function Interoggreport()
    {
        return $this->hasMany(InterogationRecord::class, 'case_id', 'id');
    }

    public function InteroggTarget()
    {
        return $this->hasMany(InterogationTargetIdentification::class, 'case_id', 'id');
    }

    public function interogachievement()
    {
        return $this->hasMany(InterogationResultAchievement::class, 'case_id', 'id');
    }


    

    
    public function elicitationInterview()
    {
        return $this->hasMany(ElicitationInterview::class, 'case_id', 'id');
    }

    public function eliciAdfoll()
    {
        return $this->hasMany(ElicitationAdFoll::class, 'case_id', 'id');
    }

    public function elresult()
    {
        return $this->hasMany(ElicitationResult::class, 'case_id', 'id');
    }
    
   
    public function progress()
    {
        return $this->hasOne(CaseProgresses::class, 'case_id', 'id');
    }

    public function CaseEventHistoricalUpdates()
    {
        return $this->hasMany(CaseEventHistoricalUpdates::class, 'case_id', 'id');
    }

    public function caseProgress()
    {
        return $this->hasOne(CaseProgresses::class, 'case_id', 'id');
    }
     
}
