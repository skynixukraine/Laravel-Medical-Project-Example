<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Notifications\DoctorVerifyChangedEmail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Doctor\SendChangeEmailRequestLink as SendChangeEmailRequestLinkRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/send-change-email-request-link",
 *     summary="Send verification email link to change email",
 *     description="Send verification email link to change email",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"email"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="E-mail",
 *                      description="A new e-mail",
 *                      property="email",
 *                      example="doctor@gmail.com"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(response=200, description="An e-mail has been sent"),
 *     @OA\Response(
 *         response=422,
 *         description="An email is not valid",
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
 *                              property="email",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The email field is required."
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
class SendChangeEmailRequestLink extends ApiController
{
    public function __invoke(SendChangeEmailRequestLinkRequest $request)
    {
        Auth::user()->emailVerify()->create([
            'email' => $request->email,
            'token' => Hash::make($token = Str::random(100)),
        ]);

        Auth::user()->notify(new DoctorVerifyChangedEmail($token, $request->email));
    }
}