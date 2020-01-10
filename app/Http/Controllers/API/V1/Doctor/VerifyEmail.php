<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/verify/{id}",
 *     summary="Verify doctor's email",
 *     description="Verify doctor's email",
 *     @OA\Response(response=200, description="An e-mail has been verified"),
 *     @OA\Response(response=304, description="An e-mail already verified"),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid signature",
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
class VerifyEmail extends ApiController
{
    public function __invoke(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 401);

        $doctor = Doctor::whereId($request->route('id'))->firstOrFail();

        if ($doctor->hasVerifiedEmail()) {
            return response('', 304);
        }

        $doctor->markEmailAsVerified();

        event(new Verified($doctor));
    }
}