<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function indexMahasiswa(Request $request)
    {
        $query = Pengaduan::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('nomor_tiket', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengaduans = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'mahasiswa.dashboard',
            compact('pengaduans')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function indexAdmin(Request $request)
    {
        $total = Pengaduan::count();

        $baru = Pengaduan::where(
            'status',
            'Menunggu Verifikasi'
        )->count();

        $proses = Pengaduan::where(
            'status',
            'Sedang Diproses'
        )->count();

        $selesai = Pengaduan::where(
            'status',
            'Selesai'
        )->count();

        $query = Pengaduan::with('user');

        if ($request->filled('kategori')) {
            $query->where(
                'kategori',
                $request->kategori
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'judul',
                    'like',
                    '%' . $search . '%'
                )->orWhere(
                    'nomor_tiket',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        $pengaduans = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.dashboard',
            compact(
                'pengaduans',
                'total',
                'baru',
                'proses',
                'selesai'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGADUAN BARU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:5120',
            'urgensi' => 'required|string',
        ]);

        $path = null;

        if ($request->hasFile('bukti')) {
            $path = $request
                ->file('bukti')
                ->store('bukti_pengaduan', 'public');
        }

        Pengaduan::create([
            'nomor_tiket' => 'UNSRI-' . strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'lokasi' => $request->lokasi,
            'bukti' => $path,
            'urgensi' => $request->urgensi,
            'is_anonim' => $request->has('is_anonim'),
            'status' => 'Menunggu Verifikasi',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Laporan pengaduan berhasil dikirim dengan nomor tiket!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        /*
        | Mahasiswa hanya boleh mengedit pengaduannya sendiri.
        | Admin boleh mengedit semua pengaduan.
        */

        if (
            Auth::user()->role !== 'admin' &&
            $pengaduan->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        return view(
            'pengaduan.edit',
            compact('pengaduan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PENGADUAN
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        /*
        | Cek hak akses.
        */

        if (
            Auth::user()->role !== 'admin' &&
            $pengaduan->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:5120',
            'urgensi' => 'required|string',
        ]);

        /*
        | Update data utama.
        */

        $pengaduan->judul = $request->judul;
        $pengaduan->deskripsi = $request->deskripsi;
        $pengaduan->kategori = $request->kategori;
        $pengaduan->lokasi = $request->lokasi;
        $pengaduan->urgensi = $request->urgensi;
        $pengaduan->is_anonim = $request->has('is_anonim');

        /*
        | Jika ada file baru, hapus file lama
        | kemudian simpan file baru.
        */

        if ($request->hasFile('bukti')) {

            if (
                $pengaduan->bukti &&
                Storage::disk('public')->exists($pengaduan->bukti)
            ) {
                Storage::disk('public')->delete(
                    $pengaduan->bukti
                );
            }

            $pengaduan->bukti = $request
                ->file('bukti')
                ->store('bukti_pengaduan', 'public');
        }

        /*
        | Admin boleh mengubah status,
        | alasan penolakan, dan unit penanganan.
        */

        if (Auth::user()->role === 'admin') {

            $request->validate([
                'status' => 'required|string',
                'alasan_ditolak' => 'nullable|string',
                'unit_terkait' => 'nullable|string',
            ]);

            $pengaduan->status = $request->status;
            $pengaduan->unit_terkait = $request->unit_terkait;

            if ($request->status === 'Ditolak') {
                $pengaduan->alasan_ditolak =
                    $request->alasan_ditolak;
            } else {
                $pengaduan->alasan_ditolak = null;
            }
        }

        $pengaduan->save();

        /*
        | Redirect berdasarkan role.
        */

        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with(
                    'success',
                    'Pengaduan ' .
                        $pengaduan->nomor_tiket .
                        ' berhasil diperbarui.'
                );
        }

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with(
                'success',
                'Pengaduan ' .
                    $pengaduan->nomor_tiket .
                    ' berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS PENGADUAN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        /*
        | Mahasiswa hanya boleh menghapus
        | pengaduannya sendiri.
        */

        if (
            Auth::user()->role !== 'admin' &&
            $pengaduan->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        /*
        | Hapus file bukti dari storage.
        */

        if (
            $pengaduan->bukti &&
            Storage::disk('public')->exists($pengaduan->bukti)
        ) {
            Storage::disk('public')->delete(
                $pengaduan->bukti
            );
        }

        $nomorTiket = $pengaduan->nomor_tiket;

        /*
        | Hapus data dari database.
        */

        $pengaduan->delete();

        /*
        | Redirect berdasarkan role.
        */

        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with(
                    'success',
                    'Pengaduan ' .
                        $nomorTiket .
                        ' berhasil dihapus.'
                );
        }

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with(
                'success',
                'Pengaduan ' .
                    $nomorTiket .
                    ' berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS OLEH ADMIN
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'alasan_ditolak' => 'nullable|string',
            'unit_terkait' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);

        $pengaduan->status = $request->status;

        if ($request->status === 'Ditolak') {
            $pengaduan->alasan_ditolak =
                $request->alasan_ditolak;
        } else {
            $pengaduan->alasan_ditolak = null;
        }

        if ($request->filled('unit_terkait')) {
            $pengaduan->unit_terkait =
                $request->unit_terkait;
        } else {
            $pengaduan->unit_terkait = null;
        }

        $pengaduan->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Status pengaduan ' .
                    $pengaduan->nomor_tiket .
                    ' berhasil diperbarui.'
            );
    }
}
