<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ShiftClosing;
use Carbon\Carbon;

class CheckShiftClosed
{
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();


        if(!$user){
            return $next($request);
        }


        // admin dan spv bebas
        if(in_array(
            strtolower($user->role),
            ['admin','spv']
        )){
            return $next($request);
        }



        $closed = ShiftClosing::where(
                'user_id',
                $user->id
            )
            ->whereDate(
                'tanggal',
                Carbon::today()
            )
            ->exists();



        if($closed){

            return redirect('/dashboard')
                ->with(
                    'shift_closed',
                    'Shift anda telah selesai hari ini'
                );

        }



        return $next($request);

    }
}