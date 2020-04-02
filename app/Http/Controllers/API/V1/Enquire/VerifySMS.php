<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\VerifySMS as VerifySMSRequest;
use App\Models\Enquire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Post(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/verify-sms",
 *     summary="Verify SMS verification code",
 *     description="Verify SMS verification code",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"verification_code", "recaptcha"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="Verification code",
 *                      description="Verification code",
 *                      property="verification_code",
 *                      example="95843565"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Recaptcha",
 *                      description="A recaptcha token. The action must be 'verify_sms'",
 *                      property="recaptcha"
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Verification code has been verified and an access token created",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="integer",
 *                          property="id",
 *                          example="1"
 *                      ),
 *                      @OA\Property(
 *                          format="string",
 *                          property="access_token",
 *                          example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ3ZGY5ZDdkYmY4ZmM1Mz",
 *                      ),
 *                      @OA\Property(
 *                          ref="#/components/schemas/CarbonResource",
 *                          format="object",
 *                          property="expires_at",
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
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
 *                              property="validation_code",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="Verification code is invalid."
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
class VerifySMS extends ApiController
{
    public function __invoke(VerifySMSRequest $request, Enquire $enquire)
    {
        $response = app('authy')->verifyToken($enquire->authy_id, $request->verification_code);

        throw_if(!$response->bodyvar('success'), ValidationException::withMessages([
            'verification_code' => __('Verification code is invalid'),
        ]));

        $accessToken = Str::random(100);

        $token = $enquire->token()->create([
            'access_token' => Hash::make($accessToken),
            'expires_at' => now()->addHour(),
        ]);

        return [
            'id' => $token->id,
            'access_token' => $accessToken,
            'expires_at' => $token->expires_at
        ];
    }
}