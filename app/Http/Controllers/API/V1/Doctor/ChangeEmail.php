<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorChangedEmail;
use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use App\Http\Requests\Doctor\ChangeEmail as ChangeEmailRequest;
use App\Models\EmailVerifies;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/change-email",
 *     summary="Change an email",
 *     description="Change an email",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"id", "token"},
 *                  @OA\Property(
 *                      format="int64",
 *                      title="ID",
 *                      description="A doctor's ID",
 *                      property="id",
 *                      example="2"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="token",
 *                      description="Token to verification. Must be 100 charecters long",
 *                      property="token",
 *                      example="dfgdfgdfgdfg"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(response=200, description="An e-mail has been changed"),
 *     @OA\Response(
 *         response=422,
 *         description="Some validation errors was heppened",
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
 *                              property="token",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The token is invalid."
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
class ChangeEmail extends ApiController
{
    public function __invoke(ChangeEmailRequest $request)
    {
        $doctor = Doctor::findOrFail($request->id);

        throw_if(!$doctor->emailVerify, ValidationException::withMessages([
            'token' => __('The verification token not exists')
        ]));

        throw_if(!Hash::check($request->token, $doctor->emailVerify->token), ValidationException::withMessages([
            'token' => __('The verification token is invalid')
        ]));

        throw_if($doctor->emailVerify->created_at->addHours(3)->lte(now()), ValidationException::withMessages([
            'token' => __('The verification token was expired')
        ]));

        throw_if(Doctor::whereEmail($doctor->emailVerify->email)->exists(), ValidationException::withMessages([
            'email' => __('The doctor with requested email already exists')
        ]));

        $doctor->update([
            'email' => $doctor->emailVerify->email,
            'email_verified_at' => $doctor->freshTimestamp()
        ]);

        EmailVerifies::where(['model_id' => $doctor->id, 'model_type' => Doctor::class])->delete();

        event(new DoctorChangedEmail($doctor));
    }
}