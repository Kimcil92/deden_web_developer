<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function confirmTransaction(Request $request)
    {
        // Validasi data input di sini jika diperlukan
        $productData = [
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'total_price' => $request->input('total_amount'),
        ];

        $customerData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
        ];

        $transaction = $this->transactionService->processTransaction($productData, $customerData);

        // Lakukan apa yang diperlukan setelah transaksi berhasil diproses

        return redirect()->route('home')->with('message', 'Transaction successful! Thank you for your purchase.');
    }
}
