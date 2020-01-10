<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorRequestedActivation;
use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use OpenApi\Annotations as OA;

/**
 * @OA\Patch(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/activate",
 *     summary="Create a request for activation a doctor",
 *     description="Create a request for activation a doctor",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="A doctor's identificator",
 *          in="query",
 *          example="1"
 *     ),
 *     @OA\Response(response=200, description="A request has been succesfully created"),
 *     @OA\Response(response=304, description="A request already created or a doctor activated"),
 *     @OA\Response(
 *         response=401,
 *         description="Authorization failed",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="Unauthenticated."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=403,
 *         description="Current user has not permissions to do this action",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="This action is unauthorized."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="No query results for model [App\Models\Doctor]."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
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
class Activate extends ApiController
{
    public function __invoke(Doctor $doctor): void
    {
        abort_if(
            $doctor->status === Doctor::STATUS_ACTIVATION_REQUESTED || $doctor->status === Doctor::STATUS_ACTIVATED,
            304
        );

        $doctor->update(['status' => Doctor::STATUS_ACTIVATION_REQUESTED]);

        event(new DoctorRequestedActivation($doctor));
    }
}