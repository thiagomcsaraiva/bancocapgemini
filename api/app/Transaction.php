<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['valor', 'conta_id'];

    public function conta(){
        return $this->belongsTo(Conta::class);
    }
}
