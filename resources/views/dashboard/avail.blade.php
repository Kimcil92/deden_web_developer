@extends('dashboard.layouts.main')
@section('title',' Rental Mobil')
@section('rent','show')
@section('avail','active')

@section('section')

    <!-- Main Content -->
    <div id="content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Mobil Tidak Tersedia</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Model</th>
                            <th>Tipe</th>
                            <th>Plat Nomor</th>
                            <th>Tarif/hari</th>
                            <th>Ketersediaan</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->product_model }}</td>
                                <td>{{ $product->product_type }}</td>
                                <td>{{ $product->police_number }}</td>
                                <td>{{ $product->product_price }}</td>
                                <td>Disewa</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
