<?php

namespace App\Models;

use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollingSurvey extends Model
{
    use HasFactory, UUIDModel;

    protected $table = 'polling_survey_count';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
