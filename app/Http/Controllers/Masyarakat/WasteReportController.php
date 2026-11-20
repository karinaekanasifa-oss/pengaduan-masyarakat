<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteReport;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WasteReportController extends Controller
{
    // Index untuk menampilkan daftar riwayat laporan sampah milik masyarakat yang sedang login
    public function index()
    {
        // Ini buat manggil data laporan dari model WasteReport khusus user yang login
        $waste_reports = WasteReport::with('zona')->where('id_pelapor', Auth::id())->get();

        // Pemanggilan folder pakenya titik (.) gak pakai slash lagi (/)
        // Setiap buat variabel baru harus masuk di compact biar bisa dipanggil di view
        return view('masyarakat.pages.riwayat_laporan', compact('waste_reports'));
    }

    // Menampilkan halaman form tambah laporan pengaduan sampah baru
    public function create()
    {
        // Ambil data zona buat pilihan dropdown di form
        $zones = Zone::get();
        return view('masyarakat.pages.tambah_laporan', compact('zones'));
    }

    // Menyimpan data laporan baru beserta file gambar ke database
    public function store(Request $request)
    {
        // Validasi data inputan termasuk file gambar kondisi awal sampah
        $request->validate([
            'id_zona' => 'required|exists:zones,id_zona',
            'jenis_sampah' => 'required|in:organik,b3,campuran',
            'lokasi' => 'required|string|max:255',
            'ulasan_warga' => 'nullable|string',
            'foto_kondisi_awal' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Proses untuk masukin dan merapikan nama file gambar pakai slug
        $slug = Str::slug($request->lokasi, '-');
        $filename = time() . '-' . $slug . '.' . $request->file('foto_kondisi_awal')->getClientOriginalExtension();

        // Untuk masukin file ke folder public/storage/folder-laporan
        $path = $request->file('foto_kondisi_awal')->storeAs('folder-laporan', $filename, 'public');

        // Simpan data ke database menggunakan Model WasteReport
        WasteReport::create([
            'id_pelapor' => Auth::id(), // ID masyarakat yang sedang login
            'id_zona' => $request->id_zona,
            'jenis_sampah' => $request->jenis_sampah,
            'lokasi' => $request->lokasi,
            'ulasan_warga' => $request->ulasan_warga,
            'foto_kondisi_awal' => $path,
            'status_laporan' => 'dilaporkan', // Status awal
        ]);

        // Jika berhasil, kembalikan ke halaman daftar laporan
        return redirect()->route('masyarakat.reports.index')->with('success', 'Laporan sampah berhasil ditambahkan!');
    }

    // Edit untuk menampilkan halaman update data laporan (misal mau ganti keterangan atau foto)
    public function edit($id)
    {
        // Cari data berdasarkan primary key kustom id_laporan
        $waste_report = WasteReport::where('id_laporan', $id)->firstOrFail();
        $zones = Zone::get();

        return view('masyarakat.pages.update_laporan', compact('waste_report', 'zones'));
    }

    // Update untuk memperbarui data laporan di database
    public function update(Request $request, $id)
    {
        $waste_report = WasteReport::where('id_laporan', $id)->firstOrFail();

        // Validasi data inputan
        $request->validate([
            'id_zona' => 'required|exists:zones,id_zona',
            'jenis_sampah' => 'required|in:organik,b3,campuran',
            'lokasi' => 'required|string|max:255',
            'ulasan_warga' => 'nullable|string',
            'foto_kondisi_awal' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Opsional kalau tidak mau ganti foto
        ]);

        $path = $waste_report->foto_kondisi_awal;

        // Cek apakah user mengunggah foto baru
        if ($request->hasFile('foto_kondisi_awal')) {
            // Hapus foto lama jika ada di storage
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Upload foto baru
            $slug = Str::slug($request->lokasi, '-');
            $filename = time() . '-' . $slug . '.' . $request->file('foto_kondisi_awal')->getClientOriginalExtension();
            $path = $request->file('foto_kondisi_awal')->storeAs('folder-laporan', $filename, 'public');
        }

        // Update data di database
        $waste_report->update([
            'id_zona' => $request->id_zona,
            'jenis_sampah' => $request->jenis_sampah,
            'lokasi' => $request->lokasi,
            'ulasan_warga' => $request->ulasan_warga,
            'foto_kondisi_awal' => $path,
        ]);

        return redirect()->route('masyarakat.reports.index')->with('success', 'Laporan sampah berhasil diperbarui!');
    }

    // Destroy untuk menghapus data laporan beserta file fotonya dari storage
    public function destroy($id)
    {
        $waste_report = WasteReport::where('id_laporan', $id)->firstOrFail();

        // Hapus file gambar fisik di folder storage jika ada
        if ($waste_report->foto_kondisi_awal && Storage::disk('public')->exists($waste_report->foto_kondisi_awal)) {
            Storage::disk('public')->delete($waste_report->foto_kondisi_awal);
        }

        // Hapus data dari database
        $waste_report->delete();

        return redirect()->route('masyarakat.reports.index')->with('success', 'Laporan sampah berhasil dihapus!');
    }
}
