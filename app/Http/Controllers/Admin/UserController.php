<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Index untuk menampilkan halaman daftar akun petugas
    public function index(){

       // Ini buat manggil data user yang perannya cuma 'Petugas' beserta relasi zona-nya
        $petugases = User::with('zona')->where('peran', 'Petugas')->get();

        // Panggil view pakai titik (.) dan masukkan data ke compact
        return view('admin.pages.petugas', compact('petugases'));
    }

    // Menampilkan halaman form tambah akun petugas baru
    public function create()
    {
        // Ambil data zona dulu buat pilihan dropdown di form nanti
        $zones = Zone::get();
        return view('admin.pages.tambah_petugas', compact('zones'));
    }

    // Menyimpan data petugas baru ke database
    public function store(Request $request)
    {
        // Validasi data inputan dari form
        $request->validate([
            'id_zona' => 'required|exists:zones,id_zona',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telepon' => 'required|string|max:20',
        ]);

        // Simpan data ke database dengan peran otomatis 'Petugas' dan password di-hash
        User::create([
            'id_zona' => $request->id_zona,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'peran' => 'Petugas',
        ]);

        // Jika berhasil, kembalikan ke halaman daftar petugas dengan pesan sukses
        return redirect()->route('admin.petugas.index')->with('success', 'Akun petugas berhasil ditambahkan!');
    }

    // Destroy untuk menghapus akun petugas berdasarkan ID
    public function destroy($id)
    {
        $petugas = User::where('id_pengguna', $id)->where('peran', 'Petugas')->firstOrFail();
        $petugas->delete();

        // Setelah dihapus, kembalikan ke halaman daftar petugas
        return redirect()->route('admin.petugas.index')->with('success', 'Akun petugas berhasil dihapus!');
    }
}
