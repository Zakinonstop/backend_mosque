<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingCertificate extends Model
{
    protected $fillable = [
        'background',
        'logo',
        'title',
        'sub_title1',
        'sub_title2',
        'description',
        'certifier',
        'position',
    ];
}
