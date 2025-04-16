<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

/* included models */
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::oldest()->first();

        return view('backend.admin.setting.edit', compact('setting'));
    }
    
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $setting = Setting::find($id);

        if ($request->file('logo')) {
            if ($setting->logo) {
                $imagePath = public_path(). '/storage/logo/' . $setting->logo;

                if(($setting->logo != '') || ($setting->logo != NULL)) {
                    if(file_exists(public_path(). '/storage/logo/' . $setting->logo)){
                        unlink($imagePath);
                    }
                }
            }

            $path = $request->file('logo')->store('/public/logo');
            
            $path = Str::replace('public/logo', '', $path);

            $data['logo'] = Str::replace('/', '', $path);
        }

        if ($request->file('soft_logo')) {
            if ($setting->soft_logo) {
                $imagePath = public_path(). '/storage/soft_logo/' . $setting->soft_logo;

                if(($setting->soft_logo != '') || ($setting->soft_logo != NULL)) {
                    if(file_exists(public_path(). '/storage/soft_logo/' . $setting->soft_logo)){
                        unlink($imagePath);
                    }
                }
            }

            $path = $request->file('soft_logo')->store('/public/soft_logo');
            
            $path = Str::replace('public/soft_logo', '', $path);

            $data['soft_logo'] = Str::replace('/', '', $path);
        }
        
        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}