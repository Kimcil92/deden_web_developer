@extends('dashboard.layouts.main')
@section('title',' Rental Mobil')
@section('rent','show')
@section('rental','active')

@section('section')

    <!-- Main Content -->
    <div id="content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Mobil</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Model</th>
                            <th>Tipe</th>
                            <th>Tarif/hari</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->product_model }}</td>
                                <td>{{ $product->product_type }}</td>
                                <td>{{ $product->product_price }}</td>
                                <td>{{ $product->is_active ? 'Tersedia' : 'Tidak Tersedia' }}</td>
                                <td>
                                    @if($product->is_active)
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sewaModal{{ $product->id }}">
                                            Sewa
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Sewa Modal -->
    @foreach($products as $product)
        <div class="modal fade" id="sewaModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="sewaModalLabel{{ $product->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sewaModalLabel{{ $product->id }}">Sewa Mobil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('sewa', ['id' => $product->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="tanggal_mulai">Tanggal Mulai Sewa</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai Sewa</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Sewa</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
