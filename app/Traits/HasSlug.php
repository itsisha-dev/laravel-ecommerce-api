<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });
    }

    protected function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}