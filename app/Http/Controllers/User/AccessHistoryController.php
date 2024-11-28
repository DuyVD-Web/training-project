<?php

namespace App\Http\Controllers\User;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessHistoryController
{
    public function index(Request $request)
    {
        $years = History::selectRaw('DISTINCT EXTRACT(YEAR FROM "time"::timestamp) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = History::query();
        $query->where('user_id', Auth::id());



        if ($request->has('types')) {
            $query->whereIn('type', $request->types);
        }

        if ($request->has('year')) {
            $query->whereYear('time', $request->input('year'));
            if ($request->has('month')) {
                $query->whereMonth('time', $request->input('month'));
                if ($request->has('day')) {
                    $query->whereDay('time', $request->input('day'));
                }
            }
        }

        $currentYear = $request->input('year', '');
        $currentMonth = $request->input('month', '');
        $currentDay = $request->input('day', '');

        $field = $request->input('field', 'time');
        $sort = $request->input('sort', 'desc');
        $query->orderBy($field, $sort);



        return view('user.access-history', [
            'histories' => $query->paginate(6),
            'years' => $years,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'currentDay' => $currentDay
        ]);
    }
}
