<?php

namespace App\Nova\Lenses;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\LensRequest;

class MostAnsweredLastMonth extends Lens
{

    public $name = "letzten Monat bearbeitet";

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select([
                    'users.id',
                    'users.title',
                    'users.first_name',
                    'users.last_name',
                    DB::raw('COUNT(*) as alm'),
                ])
                ->join('submissions AS answered_last_month', 'users.id', '=', 'answered_last_month.assigned_to_user_id')
                ->where('answered_last_month.answered_at', '>=', new Carbon('first day of last month'))
                ->where('answered_last_month.answered_at', '<', Carbon::today()->day(1))
                ->orderBy('alm', 'desc')
                ->groupBy('users.id')
            ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id'),
            Text::make('Name', 'name'),
            Number::make('Letzten Monat beantwortet', 'alm')
        ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'most-answered-last-month';
    }
}
