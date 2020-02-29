<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $fillable = ['nome_titular'];

    public function transacoes(){
        return $this->hasMany(Transaction::class);
    }
}
