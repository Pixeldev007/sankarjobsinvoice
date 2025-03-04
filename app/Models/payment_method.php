<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_method extends Model
{
    use HasFactory;
      // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'payment_methods';


    protected $fillable = [
        'id',
        'payment_method'
    ];
}
