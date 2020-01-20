<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\VerifySMS as VerifySMSRequest;
use App\Http\Resources\AuthToken;
use App\Models\Enquire;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Get(
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
 *     @OA\Response(
 *          response=200,
 *          description="Verification code has been verified",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          ref="#/components/schemas/AuthTokenResource",
 *                          format="object",
 *                          property="data"
 *                      )
 *                  }
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=422,
 *         description="Failed to send verification code",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="User not found."
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
class VerifySMS extends ApiController
{
    public function __invoke(VerifySMSRequest $request, Enquire $enquire)
    {
        $response = app('authy')->verifyToken($enquire->authy_id, $request->verification_code);

        throw_if(!$response->bodyvar('success'), ValidationException::withMessages([
            'verification_code' => __('Verification code is invalid'),
        ]));

        return AuthToken::make($enquire->saveToken());
    }
}