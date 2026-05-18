<?php

namespace App\Models;

use App\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satker extends Model
{
    use HasFactory, UUIDModel;

    protected $table = "master_satker";
    protected $primaryKey = 'id_satker';
    protected $guarded = [];

    protected static function booted() {
        static::creating(function($item) {
            $user = auth()->user();
            $item->created_by = $user?->id;
            $item->created_by_name = $user?->name;
        });
        
        static::updating(function($item) {
            $user = auth()->user();
            $item->updated_by = $user?->id;
            $item->updated_by_name = $user?->name;
        });
    }
}
