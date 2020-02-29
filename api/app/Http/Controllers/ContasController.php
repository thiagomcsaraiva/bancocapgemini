<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use App\Http\Requests;
// use App\Http\Controllers\Controller;

use App\Conta;
use App\Transaction;

class ContasController extends Controller
{
    public function index(){
        return Conta::all();
    }

    public function consultaSaldo(Request $request){
        if($request->filled('idConta')){
            return Transaction::where('conta_id', $request->idConta)->sum('valor');
        }else{
            return ['msg' => 'Conta não encontrada'];
        }
    }
}
