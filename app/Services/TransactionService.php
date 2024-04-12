<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionService
{
    public function processTransaction(array $productData, array $customerData)
    {
        // Simpan data transaksi ke dalam tabel transactions
        $transaction = Transaction::create([
            'name' => $customerData['name'],
            'email' => $customerData['email'],
            'phone_number' => $customerData['phone_number'],
            'total_amount' => $productData['total_price'],
        ]);

        // Simpan data detail transaksi ke dalam tabel transaction_details
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $productData['product_id'],
            'transaction_amount' => $productData['total_price'],
            'quantity' => $productData['quantity'],
            'total_amount' => $productData['total_price'],
        ]);

        // Kurangi stok produk di tabel products
        $product = Product::find($productData['product_id']);
        $product->stock -= $productData['quantity'];
        $product->save();

        return $transaction;
    }
}
