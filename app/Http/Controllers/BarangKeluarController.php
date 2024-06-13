<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\Storage;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
{
    $keyword = $request->input('keyword');

    // Query untuk mencari barang keluar berdasarkan keyword
    $rsetBarangKeluar = BarangKeluar::with('barang')
        ->whereHas('barang', function ($query) use ($keyword) {
            $query->where('merk', 'LIKE', "%$keyword%")
                  ->orWhere('seri', 'LIKE', "%$keyword%")
                  ->orWhere('spesifikasi', 'LIKE', "%$keyword%");
        })
        ->orWhere('tgl_keluar', 'LIKE', "%$keyword%")
        ->orWhere('qty_keluar', 'LIKE', "%$keyword%")
        ->paginate(10);

    return view('barangkeluar.index', compact('rsetBarangKeluar'))
        ->with('i', (request()->input('page',1)-1)*10);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abarang = Barang::all(); // Mengambil data barang
        $today = date('Y-m-d'); // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
        return view('barangkeluar.create', compact('abarang', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|integer|min:1',
            'barang_id' => 'required',
        ]);
    
        $barang = Barang::find($request->barang_id);
        $errors = [];
    
        if ($barang->stok < $request->qty_keluar) {
            $errors['errstok'] = 'Stok tidak cukup untuk keluaran barang ini.';
        }
    
        $tanggal_masuk_terbaru = BarangMasuk::where('barang_id', $request->barang_id)
                                            ->latest('tgl_masuk')
                                            ->value('tgl_masuk');
    
        if ($request->tgl_keluar < $tanggal_masuk_terbaru) {
            $errors['errtgl'] = 'Tanggal keluar tidak boleh lebih awal daripada tanggal masuk barang.';
        }
    
        if (!empty($errors)) {
            return redirect()->route('barangkeluar.create')->withErrors($errors)->withInput();
        }
    
        BarangKeluar::create([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);
    
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = BarangKeluar::find($id);

        //return $rsetBarang;

        //return view
        return view('barangkeluar.show', compact('rsetBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $abarang = Barang::all();
    $rsetBarang = BarangKeluar::find($id);
    $selectedBarang = Barang::find($rsetBarang->barang_id);

    return view('barangkeluar.edit', compact('rsetBarang', 'abarang', 'selectedBarang'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_keluar'   => 'required|date',
            'qty_keluar'   => 'required|numeric|min:1',
            'barang_id'    => 'required|exists:barang,id',
        ]);
    
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        //menampilkan pesan validasi saat mengupdate barang keluar, tanggal keluar tidak boleh melebihi mendahului tanggal masuk
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        $barangkeluar = BarangKeluar::findOrFail($id);
        $rsetBarang = Barang::findOrFail($barangkeluar->barang_id);

        // menampilkan pesan validasi bahwa saat mengupdate barang keluar, jumlah barang keluar tidak boleh melebihi stok yang tersedia
        if ($request->qty_keluar > $rsetBarang->stok + $barangkeluar->qty_keluar) {
            return redirect()->back()->withErrors(['qty_keluar' => 'Jumlah keluar melebihi stok yang tersedia'])->withInput();
        }
    
    
        $barangkeluar->update([
            'tgl_keluar'  => $tgl_keluar,
            'qty_keluar'  => $request->qty_keluar,
            'barang_id'   => $barang_id,
        ]);

        //menampilkan pesan validasi jika saat mengedit barang keluar dan tgl serta jumlah barang keluar tidak mendahului/melebihi barang masuk, maka akan menampilkan pesan barang keluar berhasil diupdate
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarang = BarangKeluar::find($id);

        //delete post
        $rsetBarang->delete();

        //redirect to index
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}