<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'stock_percent',
        'brightness'
    ];
}
