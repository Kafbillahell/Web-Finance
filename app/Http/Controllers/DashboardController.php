<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dompet = Dompet::where('user_id', $user->id)->get();
        $totalSaldo = $dompet->sum('saldo') ?? 0;
        $totalPemasukan = Transaksi::where('user_id', $user->id)
            ->whereHas('kategori', function ($query) {
                $query->where('tipe', 'pemasukan');
            })
            ->sum('nominal');
        $totalPengeluaran = Transaksi::where('user_id', $user->id)
            ->whereHas('kategori', function ($query) {
                $query->where('tipe', 'pengeluaran');
            })
            ->sum('nominal');
        $totalTabungan = Tabungan::where('user_id', $user->id)->sum('saldo');

        $monthlyTotals = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = Transaksi::where('user_id', $user->id)
                ->whereHas('kategori', function ($query) {
                    $query->where('tipe', 'pemasukan');
                })
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $expense = Transaksi::where('user_id', $user->id)
                ->whereHas('kategori', function ($query) {
                    $query->where('tipe', 'pengeluaran');
                })
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $monthlyTotals[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 10)),
                'income' => $income,
                'expense' => $expense
            ];
        }

        $kategoriTransaksi = \App\Models\Kategori::all();

        foreach ($kategoriTransaksi as $kategori) {
            $total = $kategori->transaksi()
                ->where('user_id', $user->id)
                ->sum('nominal');

            $kategori->total_nominal = $total;
        }

        $kategoriTransaksi = $kategoriTransaksi
            ->filter(function ($item) {
                return $item->total_nominal > 0;
            })
            ->sortBy('total_nominal')
            ->values();

        $recentTransactions = Transaksi::with(['dompet', 'kategori'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->each(function ($transaction) {
                if (!$transaction->kategori) {
                    $defaultCategory = Kategori::firstOrCreate([
                        'nama' => 'Uncategorized',
                        'tipe' => $transaction->tipe, 
                        'id_user' => Auth::id(),
                    ]);
                    $transaction->kategori()->associate($defaultCategory);
                }
            });

        return view('dashboard', compact('user', 'dompet', 'totalSaldo', 'totalPemasukan', 'totalPengeluaran', 'totalTabungan', 'recentTransactions', 'kategoriTransaksi', 'monthlyTotals'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ];

        $request->validate($rules);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $this->handleAvatarUpload($request->file('avatar'), $user);
            if ($avatar) {
                $user->avatar = $avatar;
            }
        }

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account
     */
    public function destroyProfile()
    {
        $user = Auth::user();

        // Delete user's avatar if it exists and it's not a default avatar
        if ($user->avatar && !str_starts_with($user->avatar, 'avatar')) {
            $this->deleteAvatar($user->avatar);
        }

        // Logout the user
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate the session
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Your account has been successfully deleted.');
    }

    /**
     * Handle avatar file upload
     */
    private function handleAvatarUpload($file, $user)
    {
        try {
            // Create avatars directory if it doesn't exist
            $avatarPath = public_path('assets/avatars');
            if (!File::exists($avatarPath)) {
                File::makeDirectory($avatarPath, 0755, true);
            }

            // Delete old avatar if it exists and it's not a default avatar
            if ($user->avatar && !str_starts_with($user->avatar, 'avatar')) {
                $this->deleteAvatar($user->avatar);
            }

            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $extension;

            // Move file to avatars directory
            $file->move($avatarPath, $filename);

            return $filename;

        } catch (\Exception $e) {
            // Log the error
            Log::error('Avatar upload failed: ' . $e->getMessage());
            
            // Return null to indicate upload failure
            return null;
        }
    }

    /**
     * Delete avatar file
     */
    private function deleteAvatar($filename)
    {
        try {
            $avatarPath = public_path('assets/avatars/' . $filename);
            if (File::exists($avatarPath)) {
                File::delete($avatarPath);
            }
        } catch (\Exception $e) {
            Log::error('Avatar deletion failed: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'dompet_id' => 'required|exists:dompets,id',
            'tipe' => 'required|in:deposit,withdraw',
        ]);

        $user = Auth::user();

        $dompet = Dompet::where('id', $request->dompet_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $amount = $request->amount;
        $tipe = $request->tipe;
        $tipeTransaksiKategori = $tipe === 'withdraw' ? 'pengeluaran' : 'pemasukan';

        $saldoSekarang = $dompet->saldo ?? 0;

        if ($tipe === 'withdraw') {
            if ($saldoSekarang < $amount) {
                return redirect()->route('dashboard')->with('error', 'Saldo tidak mencukupi untuk withdraw.');
            }
            $saldoBaru = $saldoSekarang - $amount;
        } else {
            $saldoBaru = $saldoSekarang + $amount;
        }

        $dompet->update(['saldo' => $saldoBaru]);

        $kategori = Kategori::firstOrCreate(
            ['nama' => ucfirst($tipe), 'id_user' => $user->id],
            ['tipe' => $tipeTransaksiKategori]
        );

        Transaksi::create([
            'user_id' => $user->id,
            'dompet_id' => $dompet->id,
            'kategori_id' => $kategori->id,
            'nominal' => $amount,
            'keterangan' => ucfirst($tipe) . ' saldo melalui dashboard',
        ]);

        return redirect()->route('dashboard')->with('success', ucfirst($tipe) . ' saldo berhasil.');
    }
}