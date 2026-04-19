<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Coach;
use App\Models\HydrationSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function index()
    {
        $athletes = Athlete::all();
        return view('athletes.index', compact('athletes'));
    }

    public function create(Request $request)
    {
        $name = $request->input('name', Auth::user() ? Auth::user()->name : '');
        $email = $request->input('email', Auth::user() ? Auth::user()->email : '');
        $weight = $request->input('weight', Auth::user()->weight ?? '');
        $height = $request->input('height', Auth::user()->height ?? '');
        $sport = $request->input('sport', Auth::user()->sport ?? '');
        $training_intensity = $request->input('training_intensity', Auth::user()->training_intensity ?? '');
        return view('profile.createprofile', compact('name', 'email', 'weight', 'height', 'sport', 'training_intensity'));
    }

    public function fetch($athlete_id)
    {
        $athlete = \App\Models\Athlete::where('athlete_id', $athlete_id)->first();
        if ($athlete) {
            return response()->json([
                'name' => $athlete->name,
                'sport' => $athlete->sport,
                'intensity' => $athlete->intensity,
                'training_intensity' => $athlete->intensity,
                'status' => $athlete->status,
            ]);
        }
        return response()->json(null, 404);
    }

    public function addById(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in as a coach.');
        }

        $validated = $request->validate([
            'athlete_id' => 'required|string',
        ]);

        $athlete = Athlete::where('athlete_id', $validated['athlete_id'])->first();

        if (!$athlete) {
            return back()->with('error', 'Athlete ID not found.');
        }

        $coachId = (string) $user->id;
        if ((string) $athlete->created_by_coach === $coachId) {
            return back()->with('success', 'Athlete is already in your team.');
        }

        $athlete->created_by_coach = $coachId;
        $athlete->save();

        $assignedCount = $this->assignPendingCoachSessionsToAthlete($user, $athlete);

        if ($assignedCount > 0) {
            return back()->with('success', "Athlete added to your team successfully. Assigned {$assignedCount} pending task(s).");
        }

        return back()->with('success', 'Athlete added to your team successfully.');
    }

    private function assignPendingCoachSessionsToAthlete($coachUser, Athlete $athlete): int
    {
        $coachCode = Coach::where('email', $coachUser->email)->value('coach_id');

        $coachAthleteIds = Athlete::query()
            ->where(function ($query) use ($coachUser, $coachCode) {
                $query->where('created_by_coach', (string) $coachUser->id)
                      ->orWhere('created_by_coach', $coachUser->id);

                if (!empty($coachCode)) {
                    $query->orWhere('created_by_coach', $coachCode);
                }
            })
            ->where('athlete_id', '!=', $athlete->athlete_id)
            ->pluck('athlete_id')
            ->filter()
            ->unique()
            ->values();

        if ($coachAthleteIds->isEmpty()) {
            return 0;
        }

        $pendingTemplates = HydrationSession::query()
            ->where('assigned_by_coach', true)
            ->whereNull('started_at')
            ->whereNull('completed_at')
            ->whereIn('athlete_id', $coachAthleteIds)
            ->latest('id')
            ->get();

        $assignedCount = 0;

        foreach ($pendingTemplates as $template) {
            $alreadyExists = HydrationSession::query()
                ->where('athlete_id', $athlete->athlete_id)
                ->where('assigned_by_coach', true)
                ->whereNull('started_at')
                ->whereNull('completed_at')
                ->where('sport', $template->sport)
                ->where('intensity', $template->intensity)
                ->where('planned_duration_minutes', $template->planned_duration_minutes)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            HydrationSession::create([
                'athlete_id' => $athlete->athlete_id,
                'coach_id' => $template->coach_id ?: (string) $coachUser->id,
                'assigned_by_coach' => true,
                'sport' => $template->sport,
                'intensity' => $template->intensity,
                'planned_duration_minutes' => (int) ($template->planned_duration_minutes ?? 0),
                'actual_duration_seconds' => 0,
                'temperature' => $template->temperature,
                'humidity' => $template->humidity,
                'reminder_interval_minutes' => $template->reminder_interval_minutes,
                'alerts' => 0,
                'followed' => 0,
                'ignored' => 0,
                'hydration_score' => 0,
                'started_at' => null,
                'completed_at' => null,
            ]);

            $assignedCount++;
        }

        return $assignedCount;
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an athlete profile.');
        }

        $validated = $request->validate([
            'name' => 'required',
            'sport' => 'required',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'training_intensity' => 'required',
        ]);
        // Use Athlete model's ID generation for consistency
        $athlete = Athlete::create([
            'user_id' => $user->id,
            'athlete_id' => Athlete::generateAthleteId(),
            'name' => $request->name,
            'email' => $user->email,
            'sport' => $request->sport,
            'weight' => $request->weight,
            'height' => $request->height,
            'bmi' => round($request->weight / pow($request->height / 100, 2), 2),
            'intensity' => $request->training_intensity,
            'status' => 'active',
            'created_by_coach' => $request->created_by_coach ?? null,
        ]);
        return redirect()->route('profile.athlprofile', [
            'athlete_id' => $athlete->athlete_id
        ]);
    }
    
    public function removePage()
    {
        $coachId = auth()->check() ? auth()->user()->id : null;
        $coachAthletes = $coachId
            ? Athlete::where('created_by_coach', $coachId)->get()
            : collect();

        return view('athletes.remove', compact('coachAthletes'));
    }

    public function addAthleteShowPage()
    {
        $availableAthletes = Athlete::where(function ($query) {
            $query->whereNull('created_by_coach')
                ->orWhere('created_by_coach', '')
                ->orWhere('created_by_coach', '0')
                ->orWhere('created_by_coach', 0);
        })->get();
        return view('athletes.addathlete', compact('availableAthletes'));
    }

    public function destroyById(Request $request)
    {
        $athlete = Athlete::where('athlete_id', $request->athlete_id)->first();

        if (!$athlete) {
            return back()->with('error', 'Athlete not found.');
        }

        if (is_null($athlete->created_by_coach) || $athlete->created_by_coach === '' || (string) $athlete->created_by_coach === '0') {
            return back()->with('success', 'Athlete is already in the untaken list.');
        }

        // Remove athlete from current coach list and return to untaken pool.
        $athlete->created_by_coach = null;
        $athlete->save();

        return back()->with('success', 'Athlete removed from your list and returned to untaken athletes.');
    }

    public function edit($athlete_id)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        if (!$athlete) {
            return redirect()->route('profile.create')->with('error', 'Athlete profile not found.');
        }
        return view('profile.editprofile', compact('athlete'));
    }

    public function update(Request $request, $athlete_id)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        if (!$athlete) {
            return redirect()->route('profile.create')->with('error', 'Athlete profile not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'intensity' => 'required',
        ]);

        $heightInMeter = $request->height / 100;
        $bmi = $request->weight / ($heightInMeter * $heightInMeter);

        $athlete->update([
            'name' => $request->name,
            'weight' => $request->weight,
            'height' => $request->height,
            'intensity' => $request->intensity,
            'status' => $request->status, 
            'bmi' => round($bmi, 1),
        ]);

        // Update hydration settings for this athlete
        $hydrationSetting = \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->first();
        if ($hydrationSetting) {
            $hydrationSetting->intensity = $request->intensity ?? $athlete->intensity;
            $hydrationSetting->save();
        }

        return redirect()->route('profile.athlprofile', ['athlete_id' => $athlete->athlete_id])->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePic(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        // Find athlete profile using user's email
        $athlete = Athlete::where('email', $user->email)->first();
        if (!$athlete) {
            return redirect()->back()->with('error', 'Athlete profile not found.');
        }

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = 'athlete_' . $athlete->athlete_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Save file in storage/app/public/profile_pics
            $stored = \Storage::disk('public')->putFileAs('profile_pics', $file, $filename);

            if (!$stored) {
                return redirect()->back()->with('error', 'Failed to save profile picture.');
            }

            // Delete old profile pic if not default
            if ($athlete->profile_pic && $athlete->profile_pic !== 'default.jpg') {
                \Storage::disk('public')->delete('profile_pics/' . $athlete->profile_pic);
            }

            $athlete->profile_pic = $filename;
            $athlete->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function updateHydrationSetting(Request $request)
    {
        $athlete = Auth::user();
        $hydrationSetting = \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->first();
        if ($hydrationSetting) {
            $hydrationSetting->intensity = $request->intensity ?? $athlete->intensity;
            $hydrationSetting->hydration_reminder = $request->hydration_reminder ?? $hydrationSetting->hydration_reminder;
            $hydrationSetting->break_duration = $request->break_duration ?? $hydrationSetting->break_duration;
            $hydrationSetting->break_reminder = $request->break_reminder ?? $hydrationSetting->break_reminder;
            $hydrationSetting->save();
        }
        return redirect()->back()->with('success', 'Hydration settings updated successfully.');
    }

    public function stayLoggedIn(Request $request)
    {
        $athlete = Athlete::where('email', Auth::user()->email)->first();
        if (!$athlete) {
            return response()->json(['success' => false, 'message' => 'Athlete profile not found.'], 404);
        }
        $athlete->stay_logged_in = $request->input('stay_logged_in', false);
        $athlete->save();
        return response()->json(['success' => true, 'stay_logged_in' => $athlete->stay_logged_in]);
    }

    public function show($athlete_id, Request $request)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        // Only use query/request data if the athlete model is missing (e.g., just created and not yet saved)
        $data = [
            'athlete' => $athlete,
            'name' => $athlete?->name ?? $request->get('name'),
            'email' => $athlete?->email ?? $request->get('email'),
            'weight' => $athlete?->weight ?? $request->get('weight'),
            'height' => $athlete?->height ?? $request->get('height'),
            'bmi' => $athlete?->bmi ?? ((($athlete?->weight ?? $request->get('weight')) && ($athlete?->height ?? $request->get('height')))
                ? round(($athlete?->weight ?? $request->get('weight')) / pow(($athlete?->height ?? $request->get('height')) / 100, 2), 2)
                : null),
            'sport' => $athlete?->sport ?? $request->get('sport'),
            'training_intensity' => $athlete?->intensity ?? $request->get('training_intensity'),
        ];
        return view('profile.athlprofile', $data);
    }
}