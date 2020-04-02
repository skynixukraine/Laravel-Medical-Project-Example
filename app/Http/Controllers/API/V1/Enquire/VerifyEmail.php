<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Events\DoctorVerifiedEmail;
use App\Http\Controllers\API\V1\ApiController;
use App\Models\Enquire;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Get(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/verify-email",
 *     summary="Verify enquire's email",
 *     description="Verify enquire's email",
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
 *                          example="No query results for model [App\Models\Enquire]."
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
        throw_if(!$request->hasValidSignature(), ValidationException::withMessages([
            'signature' => __('The signature : ' . $request->signature . ' is invalid.'),
        ]));

        $enquire = Enquire::findOrFail($request->query('id'));

        abort_if($enquire->hasVerifiedEmail(), 304);

        $enquire->markEmailAsVerified();

        event(new DoctorVerifiedEmail($enquire));
    }
}