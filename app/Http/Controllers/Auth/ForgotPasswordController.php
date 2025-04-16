<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

/* included models */
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function forgotPasswordPage()
    {
        return view('auth.passwords.email');
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent to your email.' 
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to send reset link. Please try again.'
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $account = User::where('email', $request->email)->first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'No user found with this email!'
                ], 404);
            }

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                $message = 'Password changed successfully.';

                if ($account->status == 4) {
                    $message .= ' Please verify your email.';
                } elseif ($account->status == 0) {
                    $message .= ' Please wait for account approval.';
                } else {
                    $message .= ' You may now log in.';
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('login'),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token. Please request a new password reset link.',
                    'redirect' => route('login'),
                ], 200);
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }
}
