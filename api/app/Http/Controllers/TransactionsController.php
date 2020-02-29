<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;

class TransactionsController extends Controller
{
    public function store(Request $request){
        if($request->filled(['valor','idConta'])){
            Transaction::create([
                'valor' => $request->valor,
                'conta_id' => $request->idConta
            ]);
        }else{
            return ['msg' => 'Não foi possível realizar a transação!'];
        }
    }
}
