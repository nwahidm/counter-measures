<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSatker extends Model
{
    use HasFactory;

    protected $table = "master_satker";
    protected $primaryKey = 'id_satker';
    protected $guarded = [];

    public function wilayah()
    {
        return $this->belongsToMany(
            MasterWilayah::class,
            'wilayah_satker',
            'id_satker',
            'id_wilayah'
        )->as('wilayah_satker')->withPivot(['id_satker', 'id_wilayah']);
    }

    public function satkerInduk(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id_satker');
    }

    public function satkerJajaran(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id_satker');
    }
}
