<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SiteSettingController extends Controller
{
    public function index()
    {
        $siteSetting = SiteSetting::first();
        return view('backend.website.view',compact('siteSetting'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'app_name' => 'required|string|max:255',
            'quote' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'theme_color_primary' => 'nullable|string|size:7', // Hex color code (e.g., #ffffff)
            'theme_color_secondary' => 'nullable|string|size:7', // Hex color code (e.g., #000000)
            'social_links' => 'nullable|string', // It should be a string from Tokenfield
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validating the logo upload
        ]);

        // Prepare the data to be stored
        $websiteData = [
            'app_name' => $request->app_name,
            'quote' => $request->quote,
            'location' => $request->location,
            'contact_number' => $request->contact_number,
            'contact_email' => $request->contact_email,
            'theme_color_primary' => $request->theme_color_primary,
            'theme_color_secondary' => $request->theme_color_secondary,
            'social_links' => $request->social_links ? explode(',', $request->social_links) : null, // Convert to array
        ];

        // Handle the app_logo upload if present
        if ($request->has('app_logo')) {
            $logo = $request->file('app_logo');
            $name = time() . '.' . $logo->getClientOriginalExtension();

            // Delete any existing logo
            $destinationPath = public_path('/app_logo');
            File::cleanDirectory($destinationPath);

            // Move the new logo to the folder
            $logo->move($destinationPath, $name);
            $websiteData['app_logo'] = $name;
        }

        // Update or create the SiteSetting record
        $siteSetting = SiteSetting::updateOrCreate(['id' => $request->id], $websiteData);

        // Flash a success message and redirect back
        session()->flash('success', 'Website settings updated successfully');
        return redirect()->back();
    }

}
