<?php

namespace App\Http\Controllers;

use App\Models\KickCountDtls;
use App\Models\KickCountMst;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        $user_id = auth()->id();
        $now = Carbon::now();
        // 🔹 Get latest master record for this user
        $today = Carbon::now('Asia/Dhaka')->toDateString();
        $latest = DB::table('kick_count_mst')
            ->where('user_id', $user_id)
            ->whereDate('created_at', $today)
            ->orderByDesc('id')
            ->first();
        $session_started_at = $session_ended_at = $diff_hr_min  = '--';
        $total_kick = 0;
        if ($latest) {
            $latest_created_at = Carbon::parse($latest->created_at);
            $total_kick = $latest->total_kick;
            if ($latest_created_at->greaterThanOrEqualTo($now->copy()->subHours(2))) {

                $session_started_at = $latest->created_at;
                $session_ended_at   = $latest->updated_at;
                $total_kick         = $latest->total_kick;
                $diff_hr_min        = $this->diff_hr_min($session_started_at,$session_ended_at);
                $session_started_at = Carbon::parse($session_started_at)->timezone('Asia/Dhaka')->format('h:i:s A');
                $session_ended_at   = Carbon::parse($session_ended_at)->timezone('Asia/Dhaka')->format('h:i:s A');

            }
        }
        return view('frontend.pages.kick_counter', compact('session_started_at','session_ended_at','total_kick','diff_hr_min'));
    }
    public function profile()
    {

        return view('frontend.pages.profile');
    }


    public function kick_store(Request $request)
    {
        $user_id = auth()->id();
        $now = Carbon::now();
        // 🔹 Get latest master record for this user
        $latest = DB::table('kick_count_mst')
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->first();

        $session_started_at = $now;
        $session_ended_at   = $now;
        DB::beginTransaction();
        $total_kick = 1; //initialize
        try {
            if ($latest) {
                $latest_created_at = Carbon::parse($latest->created_at);
                $total_kick = $latest->total_kick+1;
                // Check if the last master record was created within 2 hours
                if ($latest_created_at->greaterThanOrEqualTo($now->copy()->subHours(2))) {
                    $session_started_at = $latest->created_at;
                    // ✅ Within 2 hours → update existing mst
                    DB::table('kick_count_mst')
                        ->where('id', $latest->id)
                        ->update([
                            'total_kick' => $total_kick,
                            'updated_at' => $now,
                        ]);

                    $mst_id = $latest->id;
                } else {
                    // ⏰ More than 2 hours → create new mst row
                    $total_kick = 1;
                    DB::table('kick_count_mst')->insert([
                        'total_kick' => $total_kick,
                        'user_id'    => $user_id,
                        'created_at' => $now
                    ]);

                    $mst_id = DB::getPdo()->lastInsertId(); // optional (to use in dtls)
                }
            } else {
                // 🆕 No previous record → create first mst
                DB::table('kick_count_mst')->insert([
                    'total_kick' => $total_kick,
                    'user_id'    => $user_id,
                    'created_at' => $now
                ]);

                $mst_id = DB::getPdo()->lastInsertId();
            }

            // 🔸 Always insert detail record
            DB::table('kick_count_dtls')->insert([
                'mst_id'        => $mst_id,
                'user_id'       => $user_id,
                'created_at'    => $now
            ]);

            DB::commit();

            $diff_hr_min = $this->diff_hr_min($session_started_at,$session_ended_at);
            // Format session start/end as 12-hour time with seconds (e.g. 12:44:13 AM)
            $session_started_at = Carbon::parse($session_started_at)->timezone('Asia/Dhaka')->format('h:i:s A');
            $session_ended_at   = Carbon::parse($session_ended_at)->timezone('Asia/Dhaka')->format('h:i:s A');

            return response()->json([
                'success'       => true,
                'message'       => 'Kick stored successfully',
                'session_start' => $session_started_at,
                'session_end'   => $session_ended_at,
                'diff_hr_min'   => $diff_hr_min,
                'total_kick'    => $total_kick
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Database error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function diff_hr_min($start_time,$ended_at)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($ended_at);


        $diffInMinutes = $start->diffInMinutes($end);

        $hours = intdiv($diffInMinutes, 60);
        $minutes = $diffInMinutes % 60;

        if ($hours > 0 && $minutes > 0) {
            $diff_hr_min = "{$hours} hr {$minutes} min";
        } elseif ($hours > 0) {
            $diff_hr_min = "{$hours} hr";
        } else {
            $diff_hr_min = "{$minutes} min";
        }
        return $diff_hr_min;
    }

    public function kick_history(Request $request)
    {
        $user_id    = auth()->id();
        $now        = Carbon::now();
        $kick_data  = DB::table('kick_count_mst','a')
        ->join('kick_count_dtls as b','a.id','=','b.mst_id')
        ->where('a.user_id',$user_id)
        ->select('a.id as mst_id','a.created_at as session_start','a.updated_at as session_end','a.total_kick','b.created_at as kick_time')
        ->orderBy('a.id', 'desc')
        ->orderBy('b.id', 'desc')
        ->get();

        $date_wise_data_array = array();
        foreach($kick_data as $v)
        {
            $date = Carbon::parse($v->session_start)->timezone('Asia/Dhaka')->format('d-m-Y');
            if(!isset($date_wise_data_array[$date]['kick'])){
                $date_wise_data_array[$date]['kick']=0;
            }
            $date_wise_data_array[$date]['kick']++;

        }
        $dataPoints = array();
        $day = 1;
        foreach($date_wise_data_array as $date => $v)
        {
            $dataPoints[] = array("label"=> $date, "y"=> $v['kick']);
            if($day==10){break;}
            $day++;
        }

        return view('frontend.pages.kick_history',compact('kick_data','dataPoints'));
    }
    
}
