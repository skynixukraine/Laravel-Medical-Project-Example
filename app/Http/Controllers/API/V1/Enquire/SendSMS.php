<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Enquire;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Get(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/send-sms",
 *     summary="Send SMS verification code",
 *     description="Send SMS verification code",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Verification code has been sent",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="SMS token was sent."
 *                      ),
 *                  }
 *              )
 *          )
 *     ),
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
 *                              property="authy",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="Failed to send verification code."
 *                              ),
 *                          ),
 *                      ),
 *                  }
 *              ),
 *          )
 *     ),
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
class SendSMS extends ApiController
{
    public function __invoke(Enquire $enquire)
    {
        $response = app('authy')->requestSms($enquire->authy_id);

        throw_if(!$response->bodyvar('success'), ValidationException::withMessages([
            'authy' => __('Failed to send verification code'),
        ]));

        return ['message' => $response->message()];
    }
}