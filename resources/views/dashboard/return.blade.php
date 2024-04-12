@extends('dashboard.layouts.main')
@section('title', 'Pengembalian Mobil')
@section('return1', 'show')
@section('return', 'active')

@section('section')
    <!-- Main Content -->
    <div id="content">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengembalian Mobil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('car-return') }}" method="POST" id="checkPoliceForm">
                    @csrf
                    <div class="form-group">
                        <label for="police_number">Nomor Polisi</label>
                        <input type="text" class="form-control" id="police_number" name="police_number" required>
                    </div>
                    <button type="button" class="btn btn-primary" id="checkPoliceButton">Cek</button>
                </form>

                <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="paymentModalBody">
                                <!-- Detail transaksi akan ditampilkan di sini -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkPoliceButton').addEventListener('click', function () {
            var policeNumber = document.getElementById('police_number').value;
            fetch('{{ route("return.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({police_number: policeNumber})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modalBody = document.getElementById('paymentModalBody');
                        modalBody.innerHTML = `
                            <p class="text-success">Nomor Polisi Valid</p>
                            <p>Total Harga: Rp ${data.transactionDetail.total_amount}</p>
                            <p>Jumlah Hari: ${data.transactionDetail.quantity}</p>
                            <form action="{{ route('return.pay') }}" method="POST">
                                @csrf
                        <input type="hidden" name="transaction_detail_id" value="${data.transactionDetail.id}">
                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="cash">Tunai</option>
                                        <option value="credit_card">Kartu Kredit</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Bayar</button>
                            </form>
                        `;
                        $('#paymentModal').modal('show');
                    } else {
                        alert('Nomor Polisi Tidak Valid');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
