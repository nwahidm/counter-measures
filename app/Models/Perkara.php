<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkara extends Model
{
    use HasFactory;

    protected $table = 'perkaras';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
