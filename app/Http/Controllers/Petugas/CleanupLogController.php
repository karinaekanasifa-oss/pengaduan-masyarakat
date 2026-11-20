<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportAssignment;
use App\Models\CleanupLog;
use App\Models\WasteReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanupLogController extends Controller
{
    // Index untuk menampilkan daftar tugas dan riwayat pembersihan yang dikerjakan petugas yang sedang login
    public function index()
    {
        // Ambil data tugas dari model ReportAssignment khusus petugas yang sedang login
        $assignments = ReportAssignment::with(['laporan.zona', 'laporan.pelapor'])
            ->where('id_petugas', Auth::id())
            ->get();

        // Ambil juga riwayat log pembersihan yang pernah dibuat petugas ini
        $cleanup_logs = CleanupLog::with('laporan')
            ->where('id_petugas', Auth::id())
            ->get();

        // Pemanggilan folder pakenya titik (.) gak pakai slash lagi (/)
        // Setiap buat variabel baru harus masuk di compact biar bisa dipanggil di view
        return view('petugas.pages.tugas_saya', compact('assignments', 'cleanup_logs'));
    }

    // Menampilkan halaman form untuk memperbarui status pengerjaan & upload foto bukti
    public function create($id_penugasan)
    {
        // Cari data penugasan berdasarkan primary key kustom id_penugasan
        $assignment = ReportAssignment::with('laporan')->where('id_penugasan', $id_penugasan)->firstOrFail();

        return view('petugas.pages.tambah_log', compact('assignment'));
    }

    // Menyimpan data log pembersihan baru beserta file foto bukti ke database
    public function store(Request $request, $id_penugasan)
    {
        // Validasi data inputan dari petugas
        $request->validate([
            'status_perubahan' => 'required|in:ditugaskan,sedangdibersihkan,selesai',
            'foto_bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto bukti kerja petugas
            'catatan_petugas' => 'nullable|string',
        ]);

        $assignment = ReportAssignment::where('id_penugasan', $id_penugasan)->firstOrFail();

        // Proses masukin file foto bukti jika petugas mengunggahnya
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $slug = Str::slug($request->status_perubahan . '-' . $assignment->id_laporan, '-');
            $filename = time() . '-' . $slug . '.' . $request->file('foto_bukti')->getClientOriginalExtension();

            // Simpan file ke folder public/storage/folder-bukti
            $path = $request->file('foto_bukti')->storeAs('folder-bukti', $filename, 'public');
        }

        // Simpan ke database menggunakan Model CleanupLog
        CleanupLog::create([
            'id_laporan' => $assignment->id_laporan,
            'id_petugas' => Auth::id(),
            'status_perubahan' => $request->status_perubahan,
            'foto_bukti' => $path,
            'catatan_petugas' => $request->catatan_petugas,
            'waktu_pembaruan' => now(),
        ]);

        // Update juga status laporan utama di tabel waste_reports biar sinkron
        $report = WasteReport::where('id_laporan', $assignment->id_laporan)->firstOrFail();
        $statusDb = ($request->status_perubahan == 'selesai') ? 'dibersihkan' : 'ditugaskan';

        $report->update([
            'status_laporan' => $statusDb
        ]);

        // Jika berhasil, kembalikan ke halaman daftar tugas petugas
        return redirect()->route('petugas.tugas.index')->with('success', 'Progres pembersihan berhasil ditambahkan!');
    }

    // Edit untuk menampilkan halaman update log pembersihan (misal salah upload foto atau catatan)
    public function edit($id)
    {
        // Cari data log berdasarkan primary key kustom id_log
        $cleanup_log = CleanupLog::with('laporan')->where('id_log', $id)->firstOrFail();

        return view('petugas.pages.update_log', compact('cleanup_log'));
    }

    // Update untuk memperbarui data log pembersihan dan mengganti foto bukti jika ada yang baru
    public function update(Request $request, $id)
    {
        $cleanup_log = CleanupLog::where('id_log', $id)->firstOrFail();

        // Validasi data inputan
        $request->validate([
            'status_perubahan' => 'required|in:ditugaskan,sedangdibersihkan,selesai',
            'foto_bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan_petugas' => 'nullable|string',
        ]);

        $path = $cleanup_log->foto_bukti;

        // Cek apakah petugas mengunggah foto bukti baru
        if ($request->hasFile('foto_bukti')) {
            // Hapus foto lama dari storage jika ada
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Simpan foto bukti baru
            $slug = Str::slug($request->status_perubahan . '-' . $cleanup_log->id_laporan, '-');
            $filename = time() . '-' . $slug . '.' . $request->file('foto_bukti')->getClientOriginalExtension();
            $path = $request->file('foto_bukti')->storeAs('folder-bukti', $filename, 'public');
        }

        // Update data di database
        $cleanup_log->update([
            'status_perubahan' => $request->status_perubahan,
            'foto_bukti' => $path,
            'catatan_petugas' => $request->catatan_petugas,
            'waktu_pembaruan' => now(),
        ]);

        // Sinkronkan status di tabel waste_reports
        $report = WasteReport::where('id_laporan', $cleanup_log->id_laporan)->firstOrFail();
        $statusDb = ($request->status_perubahan == 'selesai') ? 'dibersihkan' : 'ditugaskan';
        $report->update(['status_laporan' => $statusDb]);

        return redirect()->route('petugas.tugas.index')->with('success', 'Log pembersihan berhasil diperbarui!');
    }

    // Destroy untuk menghapus riwayat log beserta file foto buktinya dari storage
    public function destroy($id)
    {
        $cleanup_log = CleanupLog::where('id_log', $id)->firstOrFail();

        // Hapus file foto fisik di storage jika ada
        if ($cleanup_log->foto_bukti && Storage::disk('public')->exists($cleanup_log->foto_bukti)) {
            Storage::disk('public')->delete($cleanup_log->foto_bukti);
        }

        // Hapus data dari database
        $cleanup_log->delete();

        return redirect()->route('petugas.tugas.index')->with('success', 'Log pembersihan berhasil dihapus!');
    }
}
