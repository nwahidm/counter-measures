<?php

namespace App\Models\BodyCamDevice;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyCamRecordStream extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bodycam_record_stream';
    protected $guarded = [];
    protected $casts = [
        // 'tanggal_surat' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
