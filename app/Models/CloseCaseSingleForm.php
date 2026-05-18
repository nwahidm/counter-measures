<?php

namespace App\Models;

use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterSatker;

class CloseCaseSingleForm extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'close_case_single_form';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        //'foto' => 'array'
    ];

    public function satker()
    {
        return $this->belongsTo(MasterSatker::class, 'satker_id', 'id_satker');
    }


     
}
