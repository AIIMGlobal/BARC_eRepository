<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/* included models */
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserCategory;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Office;

/* included mails */
use App\Mail\EmailVerificationMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register()
    {
        $categorys = UserCategory::select('id', 'name')->where('status', 1)->orderBy('name', 'asc')->get();
        $orgs = Office::select('id', 'name')->where('status', 1)->orderBy('name', 'asc')->get();

        return view('auth.register', compact('categorys', 'orgs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en'           => 'required|string|max:255',
            'user_category_id'  => 'required|exists:user_categories,id',
            'office_id'         => 'required|exists:offices,id',
            'designation'       => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'mobile'            => 'required|string|max:14|unique:users,mobile',
            'password'          => 'required|min:6|confirmed',
            'image'             => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('userImages', 'public');
            } else {
                $imagePath = null;
            }

            DB::beginTransaction();

            $user = new User;
            
            $user->name_en           = $request->name_en;
            $user->user_category_id  = $request->user_category_id;
            $user->email             = $request->email;
            $user->mobile            = $request->mobile;
            $user->user_type         = 4;
            $user->role_id           = 4;
            $user->password          = Hash::make($request->password);
            $user->status            = 4;

            $user->save();

            $userInfo = new UserInfo;

            $userInfo->user_id      = $user->id;
            $userInfo->office_id    = $request->office_id;
            $userInfo->designation  = $request->designation;
            $userInfo->image        = $imagePath;
            $userInfo->created_by   = Auth::id();

            $userInfo->save();

            $setting = Setting::first();

            $verifyLink = url('email-verify/' . Crypt::encryptString($user->id));

            if ($user) {
                Mail::to($user->email)->send(new EmailVerificationMail($setting, $user, $verifyLink));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Registration is in progress. Please verify your email.',
                    'redirect' => route('verify', Crypt::encryptString($user->id))
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Registration failed, please try again.'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }
}
