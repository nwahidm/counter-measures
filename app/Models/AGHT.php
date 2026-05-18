<?php

namespace App\Models;

use App\Traits\UUIDModel;
use App\Models\LogWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AGHT extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'aght';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function logs()
    {
        return $this->hasMany(LogWorkflow::class, 'ref_id', 'id')->orderByDesc('action_at');
    }
}
