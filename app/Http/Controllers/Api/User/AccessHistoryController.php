<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccessHistoryResource;
use App\Models\History;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessHistoryController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        define("App\Http\Controllers\Api\User\HISTORY_PAGINATE", 6);
        $years = History::selectRaw('DISTINCT EXTRACT(YEAR FROM "time"::timestamp) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = History::query();
        $query->where('user_id', Auth::id());

        if ($request->has('types')) {
            $query->whereIn('type', $request->types);
        }

        $year = $request->input('year', []);
        $currentMonth = $request->input('month', '');
        $currentDay = $request->input('day', '');

        if (!empty($year)) {
            $query->whereIn(DB::raw('EXTRACT(YEAR FROM time)::integer'), $year);
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

        $pageSize = $request->pageSize ? $request->pageSize : HISTORY_PAGINATE;

        $histories = $query->paginate($pageSize);

        return $this->sendPaginateResponse($histories, [
            'histories' => AccessHistoryResource::collection($histories),
            'years' => $years,
            'currentMonth' => $currentMonth,
            'currentDay' => $currentDay,
            'sort' => $sort,
            'types' => $request->types,
        ]);
    }


}
