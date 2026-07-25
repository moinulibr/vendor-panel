<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded=[];

    /**
     * Human-readable Image Size Accessor
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->image_size;

        if (!$bytes || $bytes <= 0) {
            return 'N/A';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        return round($bytes / 1024, 1) . ' KB';
    }
}
