<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display landing page with service providers list and search filters.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'provider')
            ->where('is_approved', 1)
            ->whereHas('providerProfile')
            ->with(['providerProfile', 'skills', 'reviews']);

        // Filter by Skill
        if ($request->filled('skill')) {
            $skillVal = $request->skill;
            $query->whereHas('skills', function ($q) use ($skillVal) {
                if (is_numeric($skillVal)) {
                    $q->where('skills.id', $skillVal);
                } else {
                    $q->where('skills.name', 'like', '%' . $skillVal . '%');
                }
            });
        }

        // Filter by City
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by Rating
        if ($request->filled('rating') && $request->rating > 0) {
            $rating = (float) $request->rating;
            $query->where(function ($sub) {
                $sub->selectRaw('CAST(AVG(rating) AS FLOAT)')
                    ->from('reviews')
                    ->whereColumn('reviews.provider_id', 'users.id');
            }, '>=', $rating);
        }

        // Save search history if skill is searched
        if ($request->filled('skill')) {
            session()->put('last_searched_skill', $request->skill);
        }

        $providers = $query->latest()->get();

        // Sort dynamically based on Location & Search History
        if (auth()->check() || session()->has('last_searched_skill')) {
            $userCity = auth()->check() ? auth()->user()->city : null;
            $lastSkill = session()->get('last_searched_skill');

            $providers = $providers->sort(function ($a, $b) use ($userCity, $lastSkill) {
                $scoreA = 0;
                $scoreB = 0;

                // Score for matching city
                if ($userCity) {
                    if (strcasecmp($a->city, $userCity) === 0) $scoreA += 10;
                    if (strcasecmp($b->city, $userCity) === 0) $scoreB += 10;
                }

                // Score for matching search history
                if ($lastSkill) {
                    if ($a->skills->contains('name', $lastSkill)) $scoreA += 5;
                    if ($b->skills->contains('name', $lastSkill)) $scoreB += 5;
                }

                return $scoreB <=> $scoreA; // Descending order of relevancy scores
            })->values();
        }

        $skills = Skill::orderBy('name')->get();
        
        // Dynamically get all available cities from database
        $cities = User::where('role', 'provider')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city');

        return view('welcome', compact('providers', 'skills', 'cities'));
    }
}
