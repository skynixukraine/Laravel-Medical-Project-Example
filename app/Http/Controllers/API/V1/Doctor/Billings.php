<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Billing;
use App\Models\Doctor;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/billings",
 *     summary="Get billings page",
 *     description="Get billings page",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="A doctor's id",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Billings has been succesfully received",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal technical error was happened",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="Something went wrong, please try again later."
 *                      ),
 *                  }
 *              )
 *          )
 *      )
 * )
 */
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