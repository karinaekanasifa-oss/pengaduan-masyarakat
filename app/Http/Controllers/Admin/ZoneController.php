<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    // Index untuk menampilkan halaman awal daftar zona wilayah
    public function index(){

       // Ini buat manggil semua data dari model Zone ke dalam variabel
        $zones = Zone::get();

        // Pemanggilan folder pakenya titik (.), gak pake slash (/) lagi
        // Setiap buat variabel baru harus masuk di compact biar bisa dipanggil di view
        return view('admin.pages.zones', compact('zones'));
    }

    // Menampilkan halaman form tambah zona baru
    public function create()
    {
        return view('admin.pages.tambah_zona');
    }

    // Menyimpan data zona baru ke database
    public function store(Request $request)
    {
        // Validasi data inputan biar gak kosong
        $request->validate([
            'nama_zona' => 'required|string|max:255',
            'deskripsi_wilayah' => 'nullable|string',
        ]);

        // Simpan data ke database menggunakan Model Zone
        Zone::create([
            'nama_zona' => $request->nama_zona,
            'deskripsi_wilayah' => $request->deskripsi_wilayah,
        ]);

        // Jika berhasil, kembalikan ke halaman tabel zona dengan pesan sukses
        return redirect()->route('admin.zones.index')->with('success', 'Zona wilayah berhasil ditambahkan!');
    }

    // Edit untuk menampilkan halaman form ubah data zona berdasarkan ID
    public function edit($id)
    {
        $zone = Zone::where('id_zona', $id)->firstOrFail();
        return view('admin.pages.update_zona', compact('zone'));
    }

    // Update untuk memproses perubahan data di database
    public function update(Request $request, $id)
    {
        // Validasi data inputan saat di-update
        $request->validate([
            'nama_zona' => 'required|string|max:255',
            'deskripsi_wilayah' => 'nullable|string',
        ]);

        // Cari data berdasarkan ID lalu update
        $zone = Zone::where('id_zona', $id)->firstOrFail();
        $zone->update([
            'nama_zona' => $request->nama_zona,
            'deskripsi_wilayah' => $request->deskripsi_wilayah,
        ]);

        // Jika berhasil, kembalikan ke halaman tabel zona
        return redirect()->route('admin.zones.index')->with('success', 'Zona wilayah berhasil diperbarui!');
    }

    // Destroy untuk menghapus data zona
    public function destroy($id)
    {
        $zone = Zone::where('id_zona', $id)->firstOrFail();
        $zone->delete();

        // Setelah dihapus, kembalikan ke halaman tabel zona
        return redirect()->route('admin.zones.index')->with('success', 'Zona wilayah berhasil dihapus!');
    }
}
