<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{
    use HasFactory;
    protected $table = 'userrecords';
    protected $fillable = [
      
        'fullname',
        'email',
        'doj',
        'dol',
        'work_status',
        'image',
    ];
}
