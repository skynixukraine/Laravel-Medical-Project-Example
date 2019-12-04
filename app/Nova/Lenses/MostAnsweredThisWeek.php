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

class MostAnsweredThisWeek extends Lens
{

    public $name = "diese Woche bearbeitet";

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
                    DB::raw('COUNT(*) as atw'),
                ])
                ->join('submissions AS answered_this_week', 'users.id', '=', 'answered_this_week.assigned_to_user_id')
                ->where('answered_this_week.answered_at', '>=', Carbon::today()->modify("this week"))
                ->orderBy('atw', 'desc')
                ->groupBy('users.id') // , 'users.name'
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
            Number::make('Diese Woche beantwortet', 'atw')
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
        return 'most-answered-this-week';
    }
}
