<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(): View
    {
        return view('profile.profil', ['user' => $this->authenticatedUser()]);
    }

    public function updatePhoto(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $this->authenticatedUser();
        $newPhoto = $request->file('foto_profil')->store('profile', 'public');

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->update(['foto_profil' => $newPhoto]);

        return Redirect::route('profile')->with('status', 'photo-updated');
    }

    private function authenticatedUser(): User
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('web')->user();

        abort_unless($user instanceof User, 401);

        return $user;
    }
}
