<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;

class SettingController extends Controller
{
    public function logoSite()
    {
        $settings = Setting::first();
        $logo = Setting::where('logo',  '!=',  null)->first();
        $retinalogo = Setting::where('retinalogo',  '!=',  null)->first();

        return view('admin.settings.logo-site', [
            'logo' => $logo,
            'retinalogo' => $retinalogo,
            'settings' => $settings
        ]);
    }

    public function uploadLogos(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'site_retinalogo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if (!$request->hasFile('site_logo') && !$request->hasFile('site_retinalogo')) {
            return redirect()->back()->with('success', 'Please upload the logo first');
        }

        if ($request->hasFile('site_logo')) {
            $site_logo = $request->file('site_logo');
            $logoPath = $site_logo->store('uploads/logos', 'public');

            Setting::updateOrCreate(
                [],
                ['logo' => $logoPath]
            );
        }

        if ($request->hasFile('site_retinalogo')) {
            $site_retinalogo = $request->file('site_retinalogo');
            $retinaLogoPath = $site_retinalogo->store('uploads/logos', 'public');

            Setting::updateOrCreate(
                [],
                ['retinaLogo' => $retinaLogoPath]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function fontColors()
    {
        $settings = Setting::first();

        return view('admin.settings.font-colors', [
            'seettings' => $settings,
            'settings' => $settings,
        ]);
    }

    public function updateFontColors(Request $request)
    {
        $settings = Setting::first();

        Setting::updateOrCreate(
            [], // إذا كنت لا تحتاج إلى شروط معينة للبحث
            [
                'fontfamily' => $request->input('fontfamily'),
                'font_h1' => $request->input('h1'),
                'font_h2' => $request->input('h2'),
                'font_h3' => $request->input('h3'),
                'font_h4' => $request->input('h4'),
                'font_h5' => $request->input('h5'),
                'font_paragraph' => $request->input('para'),
                'body' => $request->input('body'),
                'heading' => $request->input('heading'),
                'para' => $request->input('paracolor'),
                'button' => $request->input('button'),
            ]
        );

        return redirect()->back()->with('success', 'Font updated successfully.');
    }

    public function features()
    {
        $settings = Setting::first();
        return view('admin.settings.feauters', compact('settings'));
    }

    public function information()
    {
        $settings = Setting::first();

        return view('admin.settings.information', [
            'settings' => $settings,
            'address' => AppSetting::where('key', 'address')->first(),
            'contactEmail' => AppSetting::where('key', 'contact_email')->first(),

            'facebook' => AppSetting::where('key', 'facebook')->first(),
            'twitter' => AppSetting::where('key', 'twitter')->first(),
            'youtube' => AppSetting::where('key', 'youtube')->first(),
        ]);
    }

    public function updateInformation(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        if ($request->get('address') != null) {
            AppSetting::updateOrCreate(
                ['key' => 'address'],
                [
                    'label' => 'address',
                    'value' => $request->address,
                ],
            );
        }

        if ($request->has('contact_email') != null) {
            AppSetting::updateOrCreate(
                ['key' => 'contact_email'],
                [
                    'label' => 'contact_email',
                    'value' => $request->contact_email,
                ],
            );
        }

        if ($request->get('facebook') != null) {
            AppSetting::updateOrCreate(
                ['key' => 'facebook'],
                [
                    'label' => 'facebook',
                    'value' => $request->facebook,
                ],
            );
        }

        if ($request->get('twitter') != null) {
            AppSetting::updateOrCreate(
                ['key' => 'twitter'],
                [
                    'label' => 'twitter',
                    'value' => $request->twitter,
                ],
            );
        }

        if ($request->get('youtube') != null) {
            AppSetting::updateOrCreate(
                ['key' => 'youtube'],
                [
                    'label' => 'youtube',
                    'value' => $request->youtube,
                ],
            );
        }

        return redirect()->back()->with('success', 'Information updated successfully.');
    }
}
