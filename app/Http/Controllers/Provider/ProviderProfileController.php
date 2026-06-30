<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\ProviderProfile;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProviderProfileController extends Controller
{
    /**
     * Show the profile setup/edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->providerProfile ?? new ProviderProfile();
        $skills = Skill::orderBy('name')->get();
        $userSkillIds = $user->skills()->pluck('skills.id')->toArray();
        $availabilities = $user->availabilities()->orderByRaw("CASE day_of_week WHEN 'Saturday' THEN 1 WHEN 'Sunday' THEN 2 WHEN 'Monday' THEN 3 WHEN 'Tuesday' THEN 4 WHEN 'Wednesday' THEN 5 WHEN 'Thursday' THEN 6 WHEN 'Friday' THEN 7 ELSE 8 END")->get();

        return view('provider.profile.edit', compact('user', 'profile', 'skills', 'userSkillIds', 'availabilities'));
    }

    /**
     * Update provider profile.
     */
    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'hourly_rate' => 'required|numeric|min:0|max:99999',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'skills' => 'required|array|min:1',
            'skills.*' => 'exists:skills,id',
        ]);

        $user = Auth::user();

        // Update user fields
        $user->update([
            'city' => $request->city,
            'phone' => $request->phone,
        ]);

        // Update or create provider profile
        $user->providerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $request->bio,
                'area' => $request->area,
                'hourly_rate' => $request->hourly_rate,
                'experience_years' => $request->experience_years ?? 0,
            ]
        );

        // Sync skills
        $user->skills()->sync($request->skills);

        return redirect()->route('provider.profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        // Ensure provider profile exists
        $profile = $user->providerProfile()->firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        // Delete old photo if exists
        if ($profile->profile_photo) {
            Storage::disk('public')->delete($profile->profile_photo);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $profile->update(['profile_photo' => $path]);

        return redirect()->route('provider.profile.edit')->with('success', 'Profile photo updated!');
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto()
    {
        $user = Auth::user();
        $profile = $user->providerProfile;

        if ($profile && $profile->profile_photo) {
            Storage::disk('public')->delete($profile->profile_photo);
            $profile->update(['profile_photo' => null]);
        }

        return redirect()->route('provider.profile.edit')->with('success', 'Profile photo removed.');
    }

    /**
     * Store/update availability slots.
     */
    public function updateAvailability(Request $request)
    {
        $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|string|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
        ]);

        $user = Auth::user();

        // Delete existing and re-create
        $user->availabilities()->delete();

        foreach ($request->availabilities as $slot) {
            $user->availabilities()->create([
                'day_of_week' => $slot['day_of_week'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'is_available' => true,
            ]);
        }

        return redirect()->route('provider.profile.edit')->with('success', 'Availability updated!');
    }

    /**
     * Show provider public profile.
     */
    public function show($id)
    {
        $provider = \App\Models\User::where('id', $id)
            ->where('role', 'provider')
            ->where('is_approved', 1)
            ->with(['providerProfile', 'skills', 'availabilities'])
            ->firstOrFail();

        return view('provider.profile.show', compact('provider'));
    }
}
