@extends('layouts.adm-main')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="pull-left">
		    <h2>DAFTAR BARANG KELUAR</h2>
		</div>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($error = Session::get('gagal'))
            <div class="alert alert-danger">
                <p>{{ $error }}</p>
            </div>
        @endif
        <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div  class="flex-shrink-0">
                        <a href="{{ route('barangkeluar.create') }}" class="btn btn-md btn-success my-2">TAMBAH BARANG KELUAR</a>
                        </div>
                        <!-- Form pencarian -->
                        <form  method="GET" action="{{ route('barangkeluar.index') }}" class="form-inline my-2 my-lg-0">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" name="keyword" placeholder="Search for..."
                                    aria-label="Search" aria-describedby="basic-addon2"  value="{{ request()->input('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"> <!-- Perubahan di sini: menambahkan type="submit" -->
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- Form pencarian untuk tampilan kecil (XS) -->
                        <form  method="GET" action="{{ route('barangkeluar.index') }}" class="d-sm-none form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" name="keyword" placeholder="Search for..."
                                    aria-label="Search" aria-describedby="basic-addon2" value="{{ request()->input('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"> <!-- Perubahan di sini: menambahkan type="submit" -->
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                </div>


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TGL KELUAR</th>
                            <th>QTY KELUAR</th>
                            <th>BARANG</th>
                            <th style="width: 15%">AKSI</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarangKeluar as $rowbarang)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rowbarang->tgl_keluar  }}</td>
                                <td>{{ $rowbarang->qty_keluar  }}</td>
                                <td>{{ $rowbarang->barang-> merk  }}</td>
                                
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barangkeluar.destroy', $rowbarang->id) }}" method="POST">
                                        <a href="{{ route('barangkeluar.show', $rowbarang->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barangkeluar.edit', $rowbarang->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data barang keluar belum tersedia!
                            </div>
                        @endforelse
                    </tbody>
                   
                </table>
                {!! $rsetBarangKeluar->links('pagination::bootstrap-5') !!}

            </div>
        </div>
    </div>
@endsection