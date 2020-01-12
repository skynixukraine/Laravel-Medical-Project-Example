<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Billing;
use App\Models\Doctor;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class Billings extends ApiController
{
    public function __invoke(Request $request, Doctor $doctor)
    {
        return Billing::collection(
            $doctor->billings()
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('per_page', 50))
        );
    }
}