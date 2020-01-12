<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use App\Models\Setting;
use Stripe\OAuth;
use OpenApi\Annotations as OA;
use Stripe\Stripe;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/stripe-connect",
 *     summary="Get a doctors url to connect to stripe",
 *     description="Get a doctors url to connect to stripe and current stripe account id",
 *     @OA\Response(
 *         response=200,
 *         description="Data has been succesfully received",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="url",
 *                          example="https://connect.stripe.com/oauth/authorize?scope=read_write&client_id=ca_GVeaDvdqFNbcuNimN3M9c7Z9SLVCfd1X&response_type=code"
 *                      ),
 *                      @OA\Property(
 *                          format="string",
 *                          property="stripe_account_id",
 *                          example="ca_GVeaDvdqFNbcuNimN3M9c7Z9SLVCfd1X"
 *                      )
 *                  }
 *              )
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
class StripeConnect extends ApiController
{
    public function __invoke(Doctor $doctor)
    {
        Stripe::setApiKey(Setting::fetchValue('stripe_secret_key'));
        Stripe::setClientId(Setting::fetchValue('stripe_client_id'));

        return [
            'url' => OAuth::authorizeUrl(['scope' => 'read_write']),
            'stripe_account_id' => $doctor->stripe_account_id
        ];
    }
}