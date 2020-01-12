<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorRegistered;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\AuthToken;
use App\Models\Doctor;
use App\Services\StorageService;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use OpenApi\Annotations as OA;
use App\Http\Requests\Doctor\Register as RegisterRequest;

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
 *                  required={"email", "phone_number", "password", "recaptcha", "accepted", "password_confirmation"},
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
 *                      description="Password confirmation. Must be the same as the password",
 *                      property="password_confirmation",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A recaptcha token. The action must be 'register_doctor",
 *                      property="recaptcha",
 *                  ),
 *                  @OA\Property(
 *                      format="boolean",
 *                      description="Is terms and conditions accepted",
 *                      property="accepted",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="A doctor's board certification",
 *                      property="board_certification",
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="A doctor's medical degree",
 *                      property="medical_degree",
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="A new doctor has been succesfully registered",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 properties={
 *                     @OA\Property(
 *                         ref="#/components/schemas/AuthTokenResource",
 *                         format="object",
 *                         property="data"
 *                     )
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
    public function __invoke(RegisterRequest $request, StorageService $storage)
    {
        $doctor = Doctor::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'status' => Doctor::STATUS_CREATED,
            'board_certification' => $request->board_certification ? $storage->saveDoctorsBoardCertification($request->board_certification) : null,
            'medical_degree' => $request->medical_degree ? $storage->saveDoctorsMedicalDegree($request->medical_degree) : null,
        ]);

        $token = $doctor->createToken('Personal Access Token');
        $token->token->expires_at = Passport::$tokensExpireAt;
        $token->token->saveOrFail();

        return response()->make(AuthToken::make($token), 201);
    }
}