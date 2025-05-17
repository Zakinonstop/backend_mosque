<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingPluginWordpress extends Model
{
    protected $fillable = [
        'plugin_name',
        'background',
        'price_of_brick',
        'target_brick',
    ];
}
