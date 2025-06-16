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
        // Removed restriction on admin user (ID 1)
        $user = auth()->user();
        
        // Enhanced debug data
        \Log::info('Updating user profile:', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'old_utilisateur' => $user->utilisateur,
            'old_email' => $user->email,
            'new_utilisateur' => $request->utilisateur,
            'new_email' => $request->email,
            'fillable_attributes' => $user->getFillable()
        ]);
        
        try {
            // Try direct update method
            $updated = $user->update([
                'utilisateur' => $request->utilisateur,
                'email' => $request->email
            ]);
            
            // Double-check if update was successful
            $updatedUser = \App\Models\Utilisateur::find($user->id);
            
            \Log::info('Update result:', [
                'success' => $updated,
                'db_user_after_update' => [
                    'utilisateur' => $updatedUser->utilisateur,
                    'email' => $updatedUser->email
                ],
                'memory_user_after_update' => [
                    'utilisateur' => $user->utilisateur,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Update exception:', ['error' => $e->getMessage()]);
            return back()->withErrors(['update_error' => 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage()]);
        }

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
        // Removed restriction on admin user (ID 1)
        auth()->user()->update(['motdepasse' => Hash::make($request->get('password'))]);

        return back()->withStatus(__('Mot de passe mis à jour avec succès.'));
    }
}
