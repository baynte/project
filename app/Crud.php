<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $table = 'crud';

    protected $fillable = [
        'title', 'description'
    ];

    public function author(){
        return $this->belongsTo(User::class);
    }
}
