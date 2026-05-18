<?php

namespace App\Models\Research;

use App\Models\MasterSatker;
use App\Models\OpenCase;
use App\Models\User;

use App\Models\Research\ResearchSaranTL;
use App\Models\Research\ResearchSprint;
use App\Models\Research\ResearchPotensiAght;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchLapinsus extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_lapinsus';
    protected $guarded = [];
    protected $casts = [
        'tanggal_surat' => 'datetime:Y-m-d',
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

    public function sprint()
    {
        return $this->belongsTo(ResearchSprint::class, 'id_sprint', 'id')->withTrashed();
    }

    public function researchSaranTl()
    {
        return $this->hasMany(ResearchSaranTL::class, 'id_lapinsus', 'id');
    }
}
