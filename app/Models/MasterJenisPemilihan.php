<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJenisPemilihan extends Model
{
    use HasFactory;

    protected $table = 'master_jenis_pemilihan';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
