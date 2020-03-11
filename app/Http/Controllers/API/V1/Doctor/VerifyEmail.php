<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorVerifiedEmail;
use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/verify-email",
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

        $original = rtrim($request->url().'?'.Arr::query(
                Arr::except($request->query(), 'signature')
            ), '?');

        Log::info('Start validate signature: signature - ' . $original . ' and url - ' . $request->url() . ' and path - ' . $request->path() . ' and date - ' . Carbon::now()->getTimestamp());

        throw_if(!$request->hasValidSignature(), ValidationException::withMessages([
            'signature' => __('The signature : ' . $request->signature . ' is invalid.'),
        ]));

        $doctor = Doctor::findOrFail($request->query('id'));

        abort_if($doctor->hasVerifiedEmail(), 304);

        $doctor->markEmailAsVerified();

        $doctor->activeted();

        event(new DoctorVerifiedEmail($doctor));
    }
}