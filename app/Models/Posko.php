<?php

namespace App\Models;

use App\Traits\UUIDModel;
use App\Models\PoskoInventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posko extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'posko';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id_posko';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'id_satker');
    }

    public function wilayah()
    {
        return $this->belongsTo(MasterWilayah::class, 'id_wilayah');
    }

    public function inventory()
    {
        return $this->hasMany(PoskoInventory::class, 'id_posko');
    }
}
