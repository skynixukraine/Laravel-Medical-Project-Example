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

class BestRated extends Lens
{

    public $name = "am besten bewertet";

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
                    DB::raw('COUNT(*) as number_stars'),
                    DB::raw('AVG(stars) as average_stars'),
                ])
                ->join('submissions AS answered', 'users.id', '=', 'answered.assigned_to_user_id')
                ->whereNotNull('answered.stars')
                ->orderBy('average_stars', 'desc')
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
            Number::make('Durchschnittl. Bewertung', 'average_stars', function ($value) {
                return number_format($value, 2);
            }),
            Number::make('Anzahl Bewertungen', 'number_stars')
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
        return 'best_rated';
    }
}
