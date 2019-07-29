<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class studentInfo extends Model
{
    //Database Table
    public $table = 'student_info';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
