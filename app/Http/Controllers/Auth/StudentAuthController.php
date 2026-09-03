<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function create(Request $request): View
    {
        $parameters = $request->only(['item_id', 'tanggal', 'jam', 'jumlah']);

        if (count($parameters) === 4) {
            $request->session()->put(
                'url.intended',
                route('peminjaman.confirm', $parameters)
            );
        }

        return view('user.login');
    }

    public function store(StudentLoginRequest $request): RedirectResponse
    {
        $user = User::query()
            ->where('identitas', $request->input('nis'))
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

        return redirect()->intended(route('home'));
    }
}
