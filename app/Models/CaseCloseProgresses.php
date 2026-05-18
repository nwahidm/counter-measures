<?php

namespace App\Models;

use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseCloseProgresses extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'case_close_progresses';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function case()
    {
        return $this->belongsTo(CloseCase::class, 'case_id', 'id');
    }
}
