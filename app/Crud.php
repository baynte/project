<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crud extends Model
{
    use SoftDeletes;

    protected $table = 'crud';

    protected $fillable = [
        'title', 'description'
    ];

    public function author(){
        return $this->belongsTo(User::class);
    }
}
