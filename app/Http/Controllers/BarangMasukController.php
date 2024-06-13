<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\Storage;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
    
        // Query untuk mencari barang masuk berdasarkan keyword
        $rsetBarangMasuk = BarangMasuk::with('barang')
            ->whereHas('barang', function ($query) use ($keyword) {
                $query->where('merk', 'LIKE', "%$keyword%")
                      ->orWhere('seri', 'LIKE', "%$keyword%")
                      ->orWhere('spesifikasi', 'LIKE', "%$keyword%");
            })
            ->orWhere('tgl_masuk', 'LIKE', "%$keyword%")
            ->orWhere('qty_masuk', 'LIKE', "%$keyword%")
            ->paginate(10);
    
        return view('barangmasuk.index', compact('rsetBarangMasuk'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abarang = Barang::all(); // Mengambil data barang
        $today = date('Y-m-d'); // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
        return view('barangmasuk.create', compact('abarang', 'today'));
    }

    public function store(Request $request)
    {
        //return $request;
        //validate form
        $request->validate( [
            'tgl_masuk'          => 'required',
            'qty_masuk'          => 'required',
            'barang_id'          => 'required',

        ]);

        //create post
        BarangMasuk::create([
            'tgl_masuk'             => $request->tgl_masuk,
            'qty_masuk'             => $request->qty_masuk,
            'barang_id'             => $request->barang_id,
        ]);

        //redirect to index
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = BarangMasuk::find($id);

        //return $rsetBarang;

        //return view
        return view('barangmasuk.show', compact('rsetBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $abarang = Barang::all();
    $rsetBarang = BarangMasuk::find($id);
    $selectedBarang = Barang::find($rsetBarang->barang_id);

    return view('barangmasuk.edit', compact('rsetBarang', 'abarang', 'selectedBarang'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate( [
            'tgl_masuk'          => 'required',
            'qty_masuk'          => 'required',
            'barang_id'          => 'required',

        ]);

        $rsetBarang = BarangMasuk::find($id);

            //update post without image
            $rsetBarang->update([
                'tgl_masuk'             => $request->tgl_masuk,
                'qty_masuk'             => $request->qty_masuk,
                'barang_id'             => $request->barang_id,
            ]);

        // Redirect to the index page with a success message
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    public function destroy(string $id)
    {
        $datamasuk = BarangMasuk::find($id);
        
        // Memeriksa apakah ada record di tabel BarangKeluar dengan barang_id yang sama
        $referencedInBarangKeluar = BarangKeluar::where('barang_id', $datamasuk->barang_id)->exists();

        if ($referencedInBarangKeluar) {
        // Jika ada referensi, penghapusan ditolak
        return redirect()->route('barangmasuk.index')->with(['error' => 'Data Tidak Bisa Dihapus Karena Masih Digunakan di Tabel Barang Keluar!']);
        }

        // Menghapus record di tabel BarangMasuk
        $datamasuk->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}