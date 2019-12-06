<?php

namespace App\Nova\Filters;

use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PartnerFilter extends Filter
{

    public $component = 'select-filter';

    public $name = "Platform";

    public function apply(Request $request, $query, $value)
    {
        return $query->where('partner_id', $value);
    }

    public function options(Request $request)
    {
        $partners = Partner::all();
        return $partners->pluck('id', 'name')->toArray();
    }

}
