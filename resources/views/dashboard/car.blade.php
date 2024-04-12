@extends('dashboard.layouts.main')
@section('title',' Rental Mobil')
@section('management','show')
@section('car','active')

@section('section')

    <!-- Main Content -->
    <div id="content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Management Mobil</h6>
                    </div>
                    <div class="col text-right">
                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addModal">Tambah Data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Model</th>
                            <th>Tipe</th>
                            <th>Tarif/hari</th>
                            <th>Plat Nomor</th>
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
                                <td>{{ $product->police_number }}</td>
                                <td>{{ $product->is_active ? 'Tersedia' : 'Disewa' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $product->id }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $product->id }}">
                                        Delete
                                    </button>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Mobil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('car-management.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Tipe</label>
                            <input type="text" class="form-control" id="type" name="type" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Tarif/hari</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="police_number">Plat Nomor</label>
                            <input type="text" class="form-control" id="police_number" name="police_number" required>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Ketersediaan</label>
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @foreach($products as $product)
        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data Mobil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('car-management.update', ['id' => $product->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model" name="model" value="{{ $product->product_model }}" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Tipe</label>
                                <input type="text" class="form-control" id="type" name="type" value="{{ $product->product_type }}" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Tarif/hari</label>
                                <input type="text" class="form-control" id="price" name="price" value="{{ $product->product_price }}" required>
                            </div>
                            <div class="form-group">
                                <label for="police_number">Plat Nomor</label>
                                <input type="text" class="form-control" id="police_number" name="police_number" value="{{ $product->police_number }}" required>
                            </div>
                            <div class="form-group">
                                <label for="is_active">Ketersediaan</label>
                                <select class="form-control" id="is_active" name="is_active" required>
                                    <option value="1" {{ $product->is_active == 1 ? 'selected' : '' }}>Tersedia</option>
                                    <option value="0" {{ $product->is_active == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Modal -->
    @foreach($products as $product)
        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Hapus Data Mobil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data mobil ini?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('car-management.destroy', ['id' => $product->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
