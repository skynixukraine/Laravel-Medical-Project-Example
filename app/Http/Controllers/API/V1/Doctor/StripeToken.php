<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\OAuth\InvalidGrantException;
use Stripe\OAuth;
use OpenApi\Annotations as OA;
use Stripe\Stripe;

/**
 * @OA\Patch(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/stripe-token",
 *     summary="Set stripe account id for a doctor",
 *     description="Set stripe account id for a doctor",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"code"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="Code",
 *                      description="Code from stripe",
 *                      property="code",
 *                      example="ac_GW5JDW26mx3GRGimieN78KWUzG8wwcfg"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(response=200, description="Doctor's stripe account id has been setted"),
 *     @OA\Response(
 *         response=422,
 *         description="There are some validation errors",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  title="Validation error",
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="The given data was invalid."
 *                      ),
 *                      @OA\Property(
 *                          property="errors",
 *                          format="object",
 *                          @OA\Property(
 *                              property="code",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="This authorization code has already been used. All tokens issued with this code have been revoked."
 *                              ),
 *                          ),
 *                      ),
 *                  }
 *              ),
 *          )
 *     ),
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
class StripeToken extends ApiController
{
    public function __invoke(Request $request, Doctor $doctor)
    {
        try {
            $response = OAuth::token([
                'code' => $request->code,
                'grant_type' => 'authorization_code',
            ]);
        } catch (InvalidGrantException $exception) {
            ValidationException::withMessages(['code' => __('Failed to create stripe token')]);
        }

        $doctor->update(['stripe_account_id' => $response->stripe_user_id]);
    }
}