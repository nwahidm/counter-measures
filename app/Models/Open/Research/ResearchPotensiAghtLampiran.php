<?php

namespace App\Models\Open\Research;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchPotensiAghtLampiran extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_potensi_aght_lampiran';
    protected $guarded = [];
    protected $casts = [
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

    public function researchPotensiAght()
    {
        return $this->belongsTo(ResearchPotensiAght::class, 'id_potensi_aght', 'id');
    }
}
