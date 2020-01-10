<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use App\Services\StorageService;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/register",
 *     summary="Register a new doctor",
 *     description="Register a new doctor",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"email", "phone_number", "password", "recaptcha", "accepted"},
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's e-mail",
 *                      property="email",
 *                      example="doctor@gmail.com"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's phone number",
 *                      property="phone_number",
 *                      example="+3 8(032) 345-34-34"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's password",
 *                      property="password",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's password confirmation",
 *                      property="password_confirmation",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="Recaptcha value. Action must be 'register_doctor",
 *                      property="recaptcha",
 *                  ),
 *                  @OA\Property(
 *                      format="boolean",
 *                      description="Accept terms and conditions",
 *                      property="accepted",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="Doctor's board certification",
 *                      property="board_certification",
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="Doctor's medical degree",
 *                      property="medical_degree",
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="A doctor has been succesfully registered",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 properties={
 *                     @OA\Property(
 *                         format="integer",
 *                         property="doctor_id",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         format="string",
 *                         property="access_token",
 *                         example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ3ZGY5ZDdkYmY4ZmM1Mz",
 *                     ),
 *                     @OA\Property(
 *                         ref="#/components/schemas/CarbonResource",
 *                         format="object",
 *                         property="expires_at",
 *                     ),
 *                 }
 *             )
 *         )
 *     ),
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
 *                              property="photo",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The photo field is required."
 *                              ),
 *                          ),
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
 *                              property="accepted",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The accepted field is required."
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
 *                          example="Server Error."
 *                      ),
 *                  }
 *              )
 *          )
 *      )
 * )
 */
class Register extends ApiController
{
    public function __invoke(Register $request, StorageService $storage)
    {
        $doctor = new Doctor($request->only(['email', 'phone_number']));

        $doctor->password = Hash::make($request->password);
        $doctor->status = Doctor::STATUS_CREATED;
        $doctor->board_certification = $request->board_certification
            ? $storage->saveDoctorsBoardCertification($request->board_certification)
            : null;
        $doctor->medical_degree = $request->medical_degree
            ? $storage->saveDoctorsMedicalDegree($request->medical_degree)
            : null;

        $doctor->saveOrFail();

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