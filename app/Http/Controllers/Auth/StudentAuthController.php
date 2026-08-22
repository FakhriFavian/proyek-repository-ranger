<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function create(StudentLoginRequest $request): View
    {
        return view('user.login', [
            'itemId' => $request->query('item_id'),
            'tanggal' => $request->query('tanggal'),
            'jam' => $request->query('jam'),
            'jumlah' => $request->query('jumlah', 1),
        ]);
    }

    public function store(StudentLoginRequest $request): RedirectResponse
    {
        $user = \App\Models\User::where('identitas', $request->input('nis'))
            ->whereHas('roleuser', function ($query) {
                $query->whereRaw('LOWER(role.role) = ?', ['siswa']);
            })
            ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'nis' => 'NIS atau password siswa tidak valid.',
            ]);
        }

        Auth::guard('student')->login($user);
        $request->session()->regenerate();

        return redirect()->route('peminjaman.confirm', array_filter([
            'item_id' => $request->input('item_id'),
            'tanggal' => $request->input('tanggal'),
            'jam' => $request->input('jam'),
            'jumlah' => $request->input('jumlah'),
        ]));
    }
}
