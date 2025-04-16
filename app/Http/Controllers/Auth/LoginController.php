<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

/* included models */
use App\Models\User;
use App\Models\Setting;
use App\Models\Notification;

/* included mails */
use App\Mail\UserRegistrationToAdminMail;
use App\Mail\UserRegistrationToUserMail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            if ($user->status == 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => route('admin.home'),
                ]);
            } elseif ($user->status == 4) {
                Auth::logout();

                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your account first!',
                ]);
            } else {
                Auth::logout();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Login fail. Your Account is not authorized.',
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            DB::beginTransaction();

            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if ($user->status == 1) {
                    Auth::login($user, true);

                    return redirect()->route('admin.home');
                } elseif ($user->status == 4) {
                    return redirect()->route('login')->with('warning', 'Please verify your email!');
                } else {
                    return redirect()->route('login')->with('error', 'Unauthorized User Account!');
                }
            } else {
                $newUser = User::create([
                    'name_en'   => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'user_type' => 4,
                    'role_id'   => 4,
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make('12345678'),
                    'status'    => 0,
                ]);

                $setting = Setting::first();
                $admins = User::whereIn('role_id', [1, 2])->where('status', 1)->get();

                Mail::to($newUser->email)->send(new UserRegistrationToUserMail($setting, $newUser));

                if (count($admins)) {
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new UserRegistrationToAdminMail($setting, $newUser, $admin));

                        $notification = new Notification;

                        $notification->type             = 4;
                        $notification->title            = 'New User Registration';
                        $notification->message          = 'A new user has registered.';
                        $notification->route_name       = route('admin.user.show', Crypt::encryptString($newUser->id));
                        $notification->sender_role_id   = 4;
                        $notification->sender_user_id   = $newUser->id;
                        $notification->receiver_role_id = $admin->role_id;
                        $notification->receiver_user_id = $admin->id;
                        $notification->read_status      = 0;

                        $notification->save();
                    }
                }

                DB::commit();

                return redirect()->route('login')->with('success', 'User registration successful. Please wait for account approval.');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e->getMessage());

            return redirect()->route('login')->withErrors('Something went wrong');
        }
    }
}
