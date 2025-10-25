<?php

namespace App\Http\Controllers;

use App\Models\DailyRoutine;
use Illuminate\Http\Request;

class DailyRoutineController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.pages.remainder');
    }

    public function routine_list()
    {

        $alarms = DailyRoutine::where('user_id', auth()->id())
            ->orderByRaw("STR_TO_DATE(time, '%H:%i') ASC")
            ->get();

        return response()->json($alarms);
    }


    public function store(Request $request)
    {
        $alarm = DailyRoutine::create([
            'user_id' => auth()->id(), // ✅ store logged-in user id
            'time'    => $request->time,
            'label'   => $request->label,
            'days'    => $request->days?json_encode($request->days):"",
            'remHour' => $request->remHour,
            'remMin'  => $request->remMin,
            'enabled' => $request->enabled ?? 1,
        ]);

        return response()->json($alarm);
    }

    public function update(Request $request, $id)
    {
        $alarm = DailyRoutine::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $alarm->update([
            'time'    => $request->time,
            'label'   => $request->label,
            'days'    => $request->days?json_encode($request->days):"",
            'remHour' => $request->remHour,
            'remMin'  => $request->remMin,
            'enabled' => $request->enabled ?? 1,
        ]);

        return response()->json($alarm);
    }

    public function destroy($id)
    {
        $alarm = DailyRoutine::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $alarm->delete();
        return response()->json(['success' => true]);
    }

    public function toggle(Request $request, $id)
    {
        $alarm = DailyRoutine::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $alarm->enabled = $request->input('enabled', 0);
        $alarm->save();

        return response()->json(['success' => true]);
    }

}
