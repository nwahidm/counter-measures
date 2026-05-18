<?php

namespace App\Models;

use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class VideoAudioDocuments extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'video_audio_documents';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'case_date' => 'date:Y-m-d',
    ];

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }
   
}
