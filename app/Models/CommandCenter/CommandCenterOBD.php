<?php

namespace App\Models\CommandCenter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CommandCenterOBD extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'obd_data';
    protected $guarded = [];
    protected $casts = [
        // 'tanggal_surat' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        // 'updated_date' => 'datetime:Y-m-d H:i:s'
    ];
}
