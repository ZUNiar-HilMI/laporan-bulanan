<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Http\Controllers\AuthController;

// Redirect ke login atau dashboard
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Protected routes (auth required)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $bulan = now()->month;
        $tahun = now()->year;

        $query = Kegiatan::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'approved');

        if ($user->isAnggota()) {
            $myKegiatan = Kegiatan::where('user_id', $user->id)->count();
            $myPending = Kegiatan::where('user_id', $user->id)->where('status', 'pending')->count();
            $myRevision = Kegiatan::where('user_id', $user->id)->where('status', 'revision')->count();
            $revisionKegiatan = Kegiatan::where('user_id', $user->id)->where('status', 'revision')->get();
        }

        $totalAnggaran = $query->sum('anggaran');
        $totalKegiatan = $query->count();

        // Get monthly budget statistics for the last 12 months
        $monthlyStats = [];
        $currentDate = now();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = $currentDate->copy()->subMonths($i);
            $monthBudget = Kegiatan::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'approved')
                ->sum('anggaran');
            
            $monthlyStats[] = [
                'month' => $date->translatedFormat('M Y'),
                'short_month' => $date->translatedFormat('M'),
                'budget' => $monthBudget,
            ];
        }

        return view('dashboard.index', compact('totalAnggaran', 'totalKegiatan', 'user', 'monthlyStats') + 
            (isset($myKegiatan) ? compact('myKegiatan', 'myPending', 'myRevision', 'revisionKegiatan') : []));
    });

    // Hasil laporan (semua kegiatan approved)
    Route::get('/kegiatan', function (Request $request) {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $kegiatan = Kegiatan::with('user')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'approved')
            ->get();

        $totalAnggaran = $kegiatan->sum('anggaran');

        return view('kegiatan.index', compact('kegiatan', 'bulan', 'tahun', 'totalAnggaran'));
    });

    // Kegiatan saya (anggota only)
    Route::get('/kegiatan/saya', function () {
        $kegiatan = Kegiatan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kegiatan.saya', compact('kegiatan'));
    });

    // Form edit kegiatan
    Route::get('/kegiatan/{id}/edit', function ($id) {
        $kegiatan = Kegiatan::where('user_id', Auth::id())->findOrFail($id);
        return view('kegiatan.edit', compact('kegiatan'));
    });

    // Update kegiatan (POST)
    Route::post('/kegiatan/{id}/update', function (Request $request, $id) {
        $kegiatan = Kegiatan::where('user_id', Auth::id())->findOrFail($id);
        
        $data = $request->validate([
            'nama_kegiatan' => 'required',
            'deskripsi' => 'required',
            'anggaran' => 'required|numeric',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto_sebelum' => 'nullable|image|max:3072',
            'foto_sesudah' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('foto_sebelum')) {
            // Delete old photo
            if ($kegiatan->foto_sebelum && \Storage::disk('public')->exists($kegiatan->foto_sebelum)) {
                \Storage::disk('public')->delete($kegiatan->foto_sebelum);
            }
            $data['foto_sebelum'] = $request->file('foto_sebelum')->store('kegiatan', 'public');
        }

        if ($request->hasFile('foto_sesudah')) {
            // Delete old photo
            if ($kegiatan->foto_sesudah && \Storage::disk('public')->exists($kegiatan->foto_sesudah)) {
                \Storage::disk('public')->delete($kegiatan->foto_sesudah);
            }
            $data['foto_sesudah'] = $request->file('foto_sesudah')->store('kegiatan', 'public');
        }

        // Reset status to pending and clear revision note
        $data['status'] = 'pending';
        $data['catatan_revisi'] = null;

        $kegiatan->update($data);

        return redirect('/kegiatan/saya')->with('success', 'Kegiatan berhasil diperbarui dan dikirim untuk verifikasi ulang!');
    });

    // Form tambah kegiatan
    Route::get('/kegiatan/create', function () {
        return view('kegiatan.create');
    });

    // Simpan kegiatan (POST)
    Route::post('/kegiatan', function (Request $request) {
        $data = $request->validate([
            'nama_kegiatan' => 'required',
            'deskripsi' => 'required',
            'anggaran' => 'required|numeric',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto_sebelum' => 'nullable|image|max:3072',
            'foto_sesudah' => 'nullable|image|max:3072',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('foto_sebelum')) {
            $data['foto_sebelum'] = $request->file('foto_sebelum')->store('kegiatan', 'public');
        }

        if ($request->hasFile('foto_sesudah')) {
            $data['foto_sesudah'] = $request->file('foto_sesudah')->store('kegiatan', 'public');
        }

        Kegiatan::create($data);

        return redirect('/kegiatan/saya')->with('success', 'Kegiatan berhasil dilaporkan!');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        // Halaman verifikasi
        Route::get('/verifikasi', function () {
            $pending = Kegiatan::with('user')->where('status', 'pending')->get();
            $approved = Kegiatan::where('status', 'approved')->count();
            $rejected = Kegiatan::where('status', 'rejected')->count();

            return view('admin.verifikasi', compact('pending', 'approved', 'rejected'));
        });

        // Approve kegiatan
        Route::post('/verifikasi/{id}/approve', function ($id) {
            Kegiatan::findOrFail($id)->update(['status' => 'approved']);
            return back()->with('success', 'Kegiatan berhasil disetujui!');
        });

        // Reject kegiatan (tolak permanen)
        Route::post('/verifikasi/{id}/reject', function ($id) {
            Kegiatan::findOrFail($id)->update(['status' => 'rejected']);
            return back()->with('success', 'Kegiatan ditolak.');
        });

        // Revisi kegiatan (minta revisi dengan catatan)
        Route::post('/verifikasi/{id}/revision', function (Request $request, $id) {
            $request->validate(['catatan_revisi' => 'required|string']);
            Kegiatan::findOrFail($id)->update([
                'status' => 'revision',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            return back()->with('success', 'Permintaan revisi berhasil dikirim.');
        });

        // Export Excel
        Route::get('/export-excel', function (Request $request) {
            $bulan = $request->get('bulan', now()->format('m'));
            $tahun = $request->get('tahun', now()->format('Y'));

            $filename = "laporan_kegiatan_{$bulan}_{$tahun}.xlsx";

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\KegiatanExport($bulan, $tahun),
                $filename
            );
        });

        // Export Word
        Route::get('/export-word', function (Request $request) {
            $bulan = $request->get('bulan', now()->format('m'));
            $tahun = $request->get('tahun', now()->format('Y'));

            $export = new \App\Exports\KegiatanWordExport($bulan, $tahun);
            return $export->download();
        });

        // User Management
        Route::get('/users', function () {
            $users = \App\Models\User::orderBy('role', 'asc')->orderBy('name', 'asc')->get();
            return view('admin.users', compact('users'));
        });

        Route::post('/users/{id}/toggle-role', function ($id) {
            $user = \App\Models\User::findOrFail($id);
            
            // Prevent changing own role
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Tidak dapat mengubah role diri sendiri.');
            }

            $user->role = $user->role === 'admin' ? 'anggota' : 'admin';
            $user->save();

            return back()->with('success', "Role {$user->name} berhasil diubah menjadi " . ucfirst($user->role) . "!");
        });
    });

    // Profile routes
    Route::get('/profile', function () {
        return view('profile.index', ['user' => Auth::user()]);
    });

    Route::post('/profile/update-avatar', function (Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:3072'
        ]);

        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $request->file('avatar')->store('avatars', 'public');
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    });

    Route::post('/profile/update-name', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Nama berhasil diperbarui!');
    });
});