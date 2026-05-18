<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWilayah extends Model
{
    use HasFactory;

    protected $table = "master_wilayah";
    protected $primaryKey = 'id_wilayah';
    protected $guarded = [];

    public function satker()
    {
        return $this->belongsToMany(
            MasterSatker::class,
            'wilayah_satker',
            'id_wilayah',
            'id_satker'
        )->as('wilayah_satker')->withPivot(['id_satker', 'id_wilayah']);
    }
}
