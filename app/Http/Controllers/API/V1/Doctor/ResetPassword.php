<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorResettedPassword;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Doctor\UpdatePassword as UpdatePasswordRequest;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use OpenApi\Annotations as OA;

/**
 * @OA\Patch(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/reset-password",
 *     summary="Set a new password for a doctor",
 *     description="Set a new password for a doctor",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"email", "recaptcha", "password", "password_confirmation", "token"},
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
 *                      description="A new doctor's password",
 *                      property="password",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Password confirmation",
 *                      description="A new doctor's password confirmation",
 *                      property="password_confirmation",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Token",
 *                      description="A token value taken from reset password link",
 *                      property="token",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="Recaptcha",
 *                      description="A recaptcha value. The action must be 'update_password'",
 *                      property="recaptcha",
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(response=200, description="A new password has been setted"),
 *     @OA\Response(
 *         response=422,
 *         description="There are some validation errors",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  schema="ValidationError",
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
 *                              property="marker_address",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The marker address field is required."
 *                              ),
 *                          ),
 *                          @OA\Property(
 *                              property="token",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="Token does not exists."
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
class ResetPassword extends ApiController
{
    public function __invoke(UpdatePasswordRequest $request): void
    {
        Password::broker('doctors')->reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function (Doctor $doctor, string $password) {
                $doctor->fill(['password' => $password])->saveOrFail();
                event(new DoctorResettedPassword($doctor));
            });
    }
}