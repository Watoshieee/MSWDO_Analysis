<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        if (!session('must_change_password') || !session('change_password_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.change-password');
    }

    public function change(Request $request)
    {
        if (!session('must_change_password') || !session('change_password_user_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_\-\.])[A-Za-z\d@$!%*?&#_\-\.]{8,}$/',
            ],
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $userId = session('change_password_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['must_change_password', 'change_password_user_id', 'temp_password']);

        return redirect()->route('login')
            ->with('success', 'Password changed successfully! You can now login with your new password.');
    }
}
