<?php

namespace App\Http\Controllers;

use App\Models\RoutineStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoutineStatusController extends Controller
{
    public function markCompleted(Request $request)
    {
        $alarmId = $request->input('alarm_id');
        $userId = auth()->id();
        $today = Carbon::today()->toDateString();

        $status = RoutineStatus::updateOrCreate(
            ['alarm_id' => $alarmId, 'user_id' => $userId, 'date' => $today],
            ['completed' => true]
        );

        return response()->json(['success' => true]);
    }
    public function checkStatus($id)
    {
        $userId = auth()->id();
        $today = Carbon::today()->toDateString();

        $status = RoutineStatus::where('alarm_id', $id)
            ->where('user_id', $userId)
            ->where('date', $today)
            ->first();

        return response()->json(['completed' => $status?->completed ?? false]);
    }
}
