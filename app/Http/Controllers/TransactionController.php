<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transactions;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function list(){
        $transactions = Transactions::all();
        return TransactionResource::collection($transactions);
    }
}
