<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        // Pass current settings to the view
        return view('admin.settings.index', [
            'site_name' => SiteSetting::get('site_name', 'Aeterna Pharmacy'),
            'site_logo' => SiteSetting::get('site_logo'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|max:2048',
        ]);

        // Save Text
        SiteSetting::set('site_name', $request->site_name);

        // Save Image
        if ($request->hasFile('site_logo')) {
            // Delete old logo if it exists
            $oldLogo = SiteSetting::get('site_logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);

            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}