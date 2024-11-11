<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapingLog extends Model
{
    use HasFactory;
     
        protected $fillable = [
            'status',
            'new_products',
            'updated_products',
            'error_message',
        ];
}
