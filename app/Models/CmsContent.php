<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
    protected $fillable = ['key', 'value', 'label', 'type'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('cms_contents');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('cms_contents');
        });
    }
}
