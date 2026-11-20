<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteReport;
use App\Models\ReportAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportAssignmentController extends Controller
{
    // Index untuk menampilkan daftar laporan masuk dari masyarakat yang perlu diverifikasi/ditugaskan
    public function index()
    {
        // Ambil semua data laporan sampah beserta data zona dan pelapornya
        $reports = WasteReport::with(['zona', 'pelapor'])->get();

        // Panggil view pakai titik (.) dan masukkan data ke compact
        return view('admin.pages.reports', compact('reports'));
    }

    // Menampilkan halaman form untuk menugaskan petugas kebersihan ke laporan tertentu
    public function create($id_laporan)
    {
        // Cari data laporan berdasarkan ID, kalau gak ketemu bakal error 404
        $report = WasteReport::where('id_laporan', $id_laporan)->firstOrFail();

        // Ambil daftar petugas yang ada di zona yang sama dengan laporan tersebut
        $petugases = User::where('peran', 'Petugas')->where('id_zona', $report->id_zona)->get();

        return view('admin.pages.tambah_penugasan', compact('report', 'petugases'));
    }

    // Menyimpan data penugasan baru ke database
    public function store(Request $request, $id_laporan)
    {
        // Validasi inputan form penugasan
        $request->validate([
            'id_petugas' => 'required|exists:users,id_pengguna',
            'estimasi_selesai' => 'nullable|date',
            'catatan_admin' => 'nullable|string',
        ]);

        // Simpan data ke tabel report_assignments
        ReportAssignment::create([
            'id_laporan' => $id_laporan,
            'id_petugas' => $request->id_petugas,
            'id_admin' => Auth::id() ?? 1, // Mengambil ID admin yang sedang login
            'waktu_ditugaskan' => now(),
            'catatan_admin' => $request->catatan_admin,
            'estimasi_selesai' => $request->estimasi_selesai,
        ]);

        // Update status laporan sampah di tabel waste_reports menjadi 'ditugaskan'
        $report = WasteReport::where('id_laporan', $id_laporan)->firstOrFail();
        $report->update([
            'status_laporan' => 'ditugaskan'
        ]);

        // Jika berhasil, kembalikan ke halaman daftar laporan dengan pesan sukses
        return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil ditugaskan ke petugas kebersihan!');
    }

    // Menghapus atau membatalkan penugasan laporan
    public function destroy($id)
    {
        $assignment = ReportAssignment::where('id_penugasan', $id)->firstOrFail();

        // Kembalikan status laporan ke 'dilaporkan' (belum ditugaskan)
        $report = WasteReport::where('id_laporan', $assignment->id_laporan)->firstOrFail();
        $report->update([
            'status_laporan' => 'dilaporkan'
        ]);

        // Hapus data penugasannya
        $assignment->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Penugasan laporan berhasil dibatalkan!');
    }
}
