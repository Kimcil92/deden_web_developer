<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    public function getAllProductsExceptLoggedInUser()
    {
        $loggedInUserId = Auth::id();

        return Product::where('user_id', '!=', $loggedInUserId)
            ->where('is_active', true)
            ->get();
    }

    public function getUnavailableProductsForLoggedInUser()
    {
        $loggedInUserId = Auth::id();

        $transactionDetails = TransactionDetail::where('user_id', $loggedInUserId)
            ->where('status', 'rented')
            ->get();
        if ($transactionDetails->isEmpty()) {
            return [];
        }
        return product::where('id', $transactionDetails[0]->product_id)
            ->get();
    }

    public function storeProduct($requestData)
    {
        // Validasi data input
        $validator = Validator::make($requestData, [
            'model' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'police_number' => 'required|string',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|mimes:jpeg,jpg,png',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        // Menyimpan data mobil baru
        $product = new Product();
        $product->product_model = $requestData['model'];
        $product->product_type = $requestData['type'];
        $product->product_price = $requestData['price'];
        $product->police_number = $requestData['police_number'];
        $product->is_active = $requestData['is_active'];
        $product->user_id = Auth::id();
        $product->save();

        // Menyimpan foto mobil jika ada
        if (isset($requestData['photo'])) {
            $attachment = new Attachment();
            $file = $requestData['photo'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments', $filename);
            $attachment->filename = $filename;
            $attachment->path = $path;
            $attachment->mime_type = $file->getClientMimeType();
            $attachment->extension = $file->getClientOriginalExtension();
            $attachment->group = 'product';
            $attachment->save();

            // Melampirkan foto mobil ke data mobil
            $product->attachments()->attach($attachment->id);
        }

        return [
            'success' => true,
            'message' => 'Data mobil berhasil ditambahkan.',
        ];
    }

    public function update($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
    }

    public function delete($id)
    {
        Product::destroy($id);
    }

    public function sewaProduct($productId, $tanggalMulai, $tanggalSelesai)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);
            if (!$product) {
                return ['success' => false, 'message' => 'Produk tidak ditemukan.'];
            }

            // Hitung total harga sewa berdasarkan tarif per hari
            $tarifPerHari = $product->product_price;
            $totalHari = (strtotime($tanggalSelesai) - strtotime($tanggalMulai)) / (60 * 60 * 24);
            $totalHarga = $tarifPerHari * $totalHari;

            // Buat transaksi baru
            $transaction = new Transaction();
            $transaction->name = Auth::user()->name;
            $transaction->email = Auth::user()->email;
            $transaction->phone_number = Auth::user()->phone_number;
            $transaction->total_amount = $totalHarga;
            $transaction->payment_status = 'UnPaid';
            $transaction->transaction_status = 'OnProcess';
            $transaction->save();

            // Buat detail transaksi
            $transactionDetail = new TransactionDetail();
            $transactionDetail->transaction_amount = $tarifPerHari;
            $transactionDetail->quantity = $totalHari;
            $transactionDetail->total_amount = $totalHarga;
            $transactionDetail->product_id = $product->id;
            $transactionDetail->transaction_id = $transaction->id;
            $transactionDetail->user_id = Auth::id();
            $transactionDetail->status = 'rented';
            $transactionDetail->save();

            // Update status produk menjadi tidak tersedia
            $product->is_active = false;
            $product->save();

            DB::commit();

            return ['success' => true, 'message' => 'Produk berhasil disewa.'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateTransaction($transactionId)
    {
        try {
            DB::beginTransaction();

            // Update status produk menjadi tidak tersedia
            $transactionDetail = TransactionDetail::where('transaction_id', $transactionId)->first();
            $product = Product::find($transactionDetail->product_id);
            $product->is_active = true;
            $product->save();

            // Update status transaksi menjadi Paid dan Success
            $transaction = Transaction::find($transactionId);
            $transaction->payment_status = 'Paid';
            $transaction->transaction_status = 'Success';
            $transaction->save();

            // Update status detail transaksi menjadi returned
            $transactionDetail->status = 'returned';
            $transactionDetail->save();

            DB::commit();

            return ['success' => true, 'message' => 'Transaksi berhasil diproses.'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'Gagal memproses transaksi: ' . $e->getMessage()];
        }
    }

    public function checkPoliceNumber($policeNumber)
    {
        $user_id = Auth::id();

        $transactionDetails = TransactionDetail::where('user_id', $user_id)->get();

        foreach ($transactionDetails as $transactionDetail) {
            $product = Product::find($transactionDetail->product_id);

            if ($product && $product->police_number == $policeNumber) {
                return true;
            }
        }

        // Jika tidak ditemukan, return false
        return false;
    }

    public function getTransactionDetailByPoliceNumber($policeNumber)
    {
        $user_id = Auth::id();

        $transactionDetail = TransactionDetail::where('user_id', $user_id)
            ->whereHas('product', function ($query) use ($policeNumber) {
                $query->where('police_number', $policeNumber);
            })->first();

        return $transactionDetail;
    }

    public function completeTransaction($transactionDetailId)
    {
        DB::transaction(function () use ($transactionDetailId) {
            // Update status transaksi detail menjadi returned
            $transactionDetail = TransactionDetail::findOrFail($transactionDetailId);
            $transactionDetail->status = 'returned';
            $transactionDetail->save();

            // Update status transaksi menjadi Paid dan transaction_status menjadi Success
            $transaction = $transactionDetail->transaction;
            $transaction->payment_status = 'Paid';
            $transaction->transaction_status = 'Success';
            $transaction->save();

            // Update is_active dari product untuk product tersebut kembali menjadi 0
            $product = $transactionDetail->product;
            $product->is_active = 0;
            $product->save();
        });
    }
}
