<?php

namespace App\Models\Open\Research;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchSuratPerintahMember extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'research_surat_perintah_member';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $primaryKey = 'id_surat_perintah_member';

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
}
