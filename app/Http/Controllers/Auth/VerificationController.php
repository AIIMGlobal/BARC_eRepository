<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

/* included models */
use App\Models\User;
use App\Models\Setting;
use App\Models\Notification;

/* included mails */
use App\Mail\UserRegistrationToAdminMail;
use App\Mail\UserRegistrationToUserMail;
use App\Mail\EmailVerificationMail;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('signed')->only('verify');
    //     $this->middleware('throttle:6,1')->only('verify', 'resend');
    // }

    public function verify($id)
    {
        $id = Crypt::decryptString($id);
        $user = User::where('id', $id)->where('status', 4)->first();

        if ($user) {
            return view('auth.verify', compact('user'));
        } else {
            return redirect()->back()->with('error', 'Email not found!');
        }
    }

    public function emailVerify($id)
    {
        $id = Crypt::decryptString($id);
        $user = User::where('id', $id)->first();

        try {
            DB::beginTransaction();

            if (!empty($user)) {
                if ($user->status == 4) {
                    $user->status               = 0;
                    $user->email_verified_at    = now();

                    $user->save();

                    $setting = Setting::first();
                    $admins = User::whereIn('role_id', [1, 2])->where('status', 1)->get();

                    if ($user) {
                        Mail::to($user->email)->send(new UserRegistrationToUserMail($setting, $user));

                        if (count($admins)) {
                            foreach ($admins as $admin) {
                                Mail::to($admin->email)->send(new UserRegistrationToAdminMail($setting, $user, $admin));

                                $notification = new Notification;

                                $notification->type             = 4;
                                $notification->title            = 'New User Registration';
                                $notification->message          = 'A new user has registered.';
                                $notification->route_name       = route('admin.user.show', Crypt::encryptString($user->id));
                                $notification->sender_role_id   = 4;
                                $notification->sender_user_id   = $user->id;
                                $notification->receiver_role_id = $admin->role_id;
                                $notification->receiver_user_id = $admin->id;
                                $notification->read_status      = 0;

                                $notification->save();
                            }
                        }

                        DB::commit();

                        return redirect()->route('login')->with('success', 'Email verification completed. You have successfully registered in BARC Repository Software! Please wait for account approval.');
                    }
                } else {
                    DB::commit();

                    return redirect()->route('login')->with('warning', 'Email Already Verified!');
                }
            } else {
                DB::commit();

                return redirect()->route('login')->with('warning', 'Not a valid user!');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e->getMessage());

            return redirect()->route('login')->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function resendMail($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $user = User::where('id', $id)->where('status', 4)->first();

            $setting = Setting::first();

            $verifyLink = url('email-verify/' . Crypt::encryptString($user->id));

            if ($user) {
                Mail::to($user->email)->send(new EmailVerificationMail($setting, $user, $verifyLink));

                return response()->json([
                    'success' => true,
                    'message' => 'Verification email resent.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No user account found by this email!'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }
}