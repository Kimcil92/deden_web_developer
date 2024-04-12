<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function rental(ProductService $productService)
    {
        $products = $productService->getAllProductsExceptLoggedInUser();

        return view('dashboard.rental', ['products' => $products]);
    }

    public function car()
    {
        $user_id = Auth::id();
        $products = Product::where('user_id', $user_id)->get();

        return view('dashboard.car', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $result = $this->productService->storeProduct($request->all());

        if (!$result['success']) {
            return redirect()->back()->withErrors(['photo' => $result['message']])->withInput();
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'model' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'police_number' => 'required|string',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|mimes:jpeg,jpg,png',
        ]);

        $this->productService->update($id, $validatedData);

        return redirect()->back()->with('success', 'Data mobil berhasil diupdate.');
    }

    public function destroy($id)
    {
        $this->productService->delete($id);

        return redirect()->back()->with('success', 'Data mobil berhasil dihapus.');
    }

    public function avail()
    {
        $products = $this->productService->getUnavailableProductsForLoggedInUser();

        return view('dashboard.avail', ['products' => $products]);
    }

    public function showReturnForm()
    {
        $transactionDetail = null; // Inisialisasi variabel $transactionDetail dengan null
        return view('dashboard.return', ['result' => false, 'transactionDetail' => $transactionDetail]);
    }

    public function checkPoliceNumber(Request $request)
    {
        $policeNumber = $request->input('police_number');
        $result = $this->productService->checkPoliceNumber($policeNumber);

        if ($result) {
            $transactionDetail = $this->productService->getTransactionDetailByPoliceNumber($policeNumber);
            return response()->json(['success' => true, 'transactionDetail' => $transactionDetail]);
        }

        return response()->json(['success' => false]);
    }

    public function payForReturn(Request $request)
    {
        $transactionDetailId = $request->input('transaction_detail_id');
        $transactionDetail = TransactionDetail::find($transactionDetailId);

        // Memanggil method updateTransaction dari ProductService untuk melakukan update status transaksi
        $result = $this->productService->updateTransaction($transactionDetail->transaction_id);

        if ($result['success']) {
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', $result['message']);
        } else {
            // Redirect dengan pesan error
            return redirect()->back()->with('error', $result['message']);
        }
    }


    public function sewa(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        // Proses penyewaan
        $result = $this->productService->sewaProduct($id, $validatedData['tanggal_mulai'], $validatedData['tanggal_selesai']);

        // Redirect kembali ke halaman rental
        return redirect()->route('rental')->with('success', $result['message']);
    }

}
