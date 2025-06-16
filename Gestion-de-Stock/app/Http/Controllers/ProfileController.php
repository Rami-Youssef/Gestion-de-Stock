<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('Vous n\'êtes pas autorisé à modifier les données pour un utilisateur par défaut.')]);
        }

        auth()->user()->update([
            'utilisateur' => $request->utilisateur,
            'email' => $request->email
        ]);

        return back()->withStatus(__('Profil mis à jour avec succès.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_password' => __('Vous n\'êtes pas autorisé à modifier le mot de passe pour un utilisateur par défaut.')]);
        }

        auth()->user()->update(['motdepasse' => Hash::make($request->get('password'))]);

        return back()->withStatus(__('Mot de passe mis à jour avec succès.'));
    }
}
