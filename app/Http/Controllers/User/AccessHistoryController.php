<?php

namespace App\Http\Controllers\User;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessHistoryController
{
    public function index(Request $request)
    {
        define("App\Http\Controllers\User\HISTORY_PAGINATE", 6);
        $years = History::selectRaw('DISTINCT EXTRACT(YEAR FROM "time"::timestamp) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = History::query();
        $query->where('user_id', Auth::id());

        if ($request->has('types')) {
            $query->whereIn('type', $request->types);
        }

        $currentYear = $request->input('year', '');
        $currentMonth = $request->input('month', '');
        $currentDay = $request->input('day', '');

        if ($currentYear) {
            $query->whereYear('time', $currentYear);
            if ($currentMonth) {
                $query->whereMonth('time', $currentMonth);
                if ($currentDay) {
                    $query->whereDay('time', $currentDay);
                }
            }
        }

        $field = $request->input('field', 'time');
        $sort = $request->input('sort', 'desc');
        $query->orderBy($field, $sort);

        return view('user.access-history', [
            'histories' => $query->paginate(HISTORY_PAGINATE),
            'years' => $years,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'currentDay' => $currentDay
        ]);
    }
}
