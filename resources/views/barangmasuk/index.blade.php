@extends('layouts.adm-main')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="pull-left">
		    <h2>DAFTAR BARANG MASUK</h2>
		</div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @elseif ($message = Session::get('error'))
             <div class="alert alert-danger">
                 <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div  class="flex-shrink-0">
                        <a href="{{ route('barangmasuk.create') }}" class="btn btn-md btn-success my-2">TAMBAH BARANG MASUK</a>
                        </div>
                        <!-- Form pencarian -->
                        <form  method="GET" action="{{ route('barangmasuk.index') }}" class="form-inline my-2 my-lg-0">
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
                        <form  method="GET" action="{{ route('barangmasuk.index') }}" class="d-sm-none form-inline mr-auto w-100 navbar-search">
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


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TGL MASUK</th>
                            <th>QTY MASUK</th>
                            <th>BARANG</th>
                            <th style="width: 15%">AKSI</th>


                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 0;
                        @endphp
                    @forelse ($rsetBarangMasuk as $rowbarang)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rowbarang->tgl_masuk  }}</td>
                                <td>{{ $rowbarang->qty_masuk  }}</td>
                                <td>{{ $rowbarang->barang-> merk  }}</td>
                                
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barangmasuk.destroy', $rowbarang->id) }}" method="POST">
                                        <a href="{{ route('barangmasuk.show', $rowbarang->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barangmasuk.edit', $rowbarang->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data barang masuk belum tersedia!
                            </div>
                        @endforelse
                    </tbody>
                   
                </table>
                {!! $rsetBarangMasuk->links('pagination::bootstrap-5') !!}

            </div>
        </div>
    </div>
@endsection