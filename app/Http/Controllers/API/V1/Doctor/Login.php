<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Doctor\Login as LoginRequest;
use App\Http\Resources\AuthToken;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/login",
 *     summary="Create a new token for a doctor",
 *     description="Create a new token for a doctor",
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
 *                      description="A recaptcha token. The action must be 'login_doctor'",
 *                      property="recaptcha"
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="An authorization token has been created",
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
 *         description="An authorization attempt has been failed",
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
    public function __invoke(LoginRequest $request)
    {
        $doctor = Doctor::whereEmail($request->email)->firstOrFail();

        throw_if(!$doctor->email_verified_at, ValidationException::withMessages([
            'email' => __('Please verify your e-mail address to login'),
        ]));

        throw_if($doctor->status === Doctor::STATUS_CLOSED, ValidationException::withMessages([
            'email' => __('Your account has been closed'),
        ]));

        throw_if(!Hash::check($request->password, $doctor->password), ValidationException::withMessages([
            'password' => __('The password is invalid.'),
        ]));

        throw_if($doctor->status === Doctor::STATUS_CREATED, ValidationException::withMessages([
            'password' => __('Your account is not activated.'),
        ]));

        return AuthToken::make($doctor->saveToken());
    }
}