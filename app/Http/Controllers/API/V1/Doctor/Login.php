<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/login",
 *     summary="Create a token for a doctor",
 *     description="Create a token for a doctor",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"email", "password", "recaptcha"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="E-mail",
 *                      description="A doctor's e-mail",
 *                      property="email",
 *                      example="doctor@gmail.com"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Password",
 *                      description="A doctor's password",
 *                      property="password",
 *                      example="12345678"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Recaptcha",
 *                      description="Recaptcha value. Action must be 'login_doctor'",
 *                      property="recaptcha"
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Token has been created",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="integer",
 *                          property="doctor_id",
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
 *                              property="email",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The email field is required."
 *                              ),
 *                          ),
 *                          @OA\Property(
 *                              property="password",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The password field is required."
 *                              ),
 *                          ),
 *                          @OA\Property(
 *                              property="recaptcha",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The recaptcha field is required."
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
class Login extends ApiController
{
    public function __invoke(Login $request)
    {
        $doctor = Doctor::whereEmail($request->input('email'))->first();

        if (!$doctor || !Hash::check($request->input('password'), $doctor->password)) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $token = $doctor->createToken('Personal Access Token');
        $token->token->expires_at = Passport::$tokensExpireAt;
        $token->token->save();

        return response()->json(
            [
                'doctor_id' => $doctor->id,
                'access_token' => $token->accessToken,
                'expires_at' => $token->token->expires_at,
            ]
        );
    }
}