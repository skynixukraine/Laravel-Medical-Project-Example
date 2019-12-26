<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Events\DoctorRegistered;
use App\Http\Requests\Doctor\Login;
use App\Http\Requests\Doctor\Register;
use App\Http\Requests\Doctor\SendResetLink;
use App\Http\Requests\Doctor\Update;
use App\Http\Requests\Doctor\UpdatePassword;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Location;
use App\Services\StorageService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Passport;
use OpenApi\Annotations as OA;

class DoctorController extends ApiController
{
    /**
     * @OA\Post(
     *     tags={"Doctors"},
     *     path="/api/v1/register",
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
     *                  @OA\Property(
     *                      format="binary",
     *                      description="A doctor's photo",
     *                      property="photo",
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="A doctor has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          ref="#/components/schemas/DoctorResource",
     *                          property="data"
     *                      )
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
    public function register(Register $request, StorageService $storage)
    {
        $doctor = new Doctor($request->only(['email', 'phone_number']));

        $doctor->password = Hash::make($request->password);
        $doctor->status = Doctor::STATUS_CREATED;
        $doctor->photo = $request->photo ? $storage->saveDoctorsPhoto($request->photo) : null;
        $doctor->board_certification = $request->board_certification
            ? $storage->saveDoctorsBoardCertification($request->board_certification)
            : null;
        $doctor->medical_degree = $request->medical_degree
            ? $storage->saveDoctorsMedicalDegree($request->medical_degree)
            : null;

        $doctor->saveOrFail();

        event(new DoctorRegistered($doctor));

        return DoctorResource::make($doctor);
    }

    /**
     * @OA\Get(
     *     tags={"Doctors"},
     *     path="/api/v1/verify/{id}",
     *     summary="Verify doctor's email",
     *     description="Verify doctor's email",
     *     @OA\Response(response=200, description="An e-mail has been verified"),
     *     @OA\Response(response=304, description="An e-mail already verified"),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid signature",
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
     *         response=404,
     *         description="Resource not found",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function verify(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 401);

        $doctor = Doctor::whereId($request->route('id'))->firstOrFail();

        if ($doctor->hasVerifiedEmail()) {
            return response('', 304);
        }

        $doctor->markEmailAsVerified();

        event(new Verified($doctor));
    }

    /**
     * @OA\Get(
     *     tags={"Doctors"},
     *     path="/api/v1/resend/{id}",
     *     summary="Resend verification email",
     *     description="Verify doctor's email",
     *     @OA\Response(response=200, description="An e-mail has been sent"),
     *     @OA\Response(response=304, description="An e-mail already verified"),
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
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function resend(Request $request)
    {
        $doctor = Doctor::whereId($request->route('id'))->firstOrFail();

        if ($doctor->hasVerifiedEmail()) {
            return response('', 304);
        }

        $doctor->sendEmailVerificationNotification();
    }

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
     *                          format="string",
     *                          property="access_token",
     *                          example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ3ZGY5ZDdkYmY4ZmM1Mz",
     *                      ),
     *                      @OA\Property(
     *                          format="string",
     *                          property="token_type",
     *                          example="Bearer",
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
    public function login(Login $request)
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
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->token->expires_at,
            ]
        );
    }

    /**
     * @OA\Post(
     *     tags={"Doctors"},
     *     path="/api/v1/logout",
     *     summary="Revoke current token",
     *     description="Revoke current token",
     *     @OA\Response(response=200, description="Token has been revoked"),
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
    public function logout(Request $request): void
    {
        $request->user()->token()->revoke();
    }

    /**
     * @OA\Post(
     *     tags={"Doctors"},
     *     path="/api/v1/send-reset-link",
     *     summary="Send reset password link",
     *     description="Send email message for a doctor with a link for password reseting",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"email", "recaptcha"},
     *                  @OA\Property(
     *                      format="string",
     *                      title="E-mail",
     *                      description="A doctor's e-mail",
     *                      property="email",
     *                      example="doctor@gmail.com"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Recaptcha",
     *                      description="Recaptcha value. Action must be 'send_reset_link'",
     *                      property="recaptcha",
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(response=200, description="An e-mail has been sent"),
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
    public function sendResetLinkEmail(SendResetLink $request): void
    {
        $response = Password::broker('doctors')->sendResetLink($request->only('email'));

        abort_if($response !== Password::RESET_LINK_SENT, 500, __('Something went wrong, please try again later'));
    }

    /**
     * @OA\Patch(
     *     tags={"Doctors"},
     *     path="/api/v1/update-password",
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
     *                      description="Token value taken from reset password link",
     *                      property="token",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Recaptcha",
     *                      description="Recaptcha value. Action must be 'update_password'",
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
    public function updatePassword(UpdatePassword $request): void
    {
        $response = Password::broker('doctors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        abort_if($response !== Password::PASSWORD_RESET, 500, __('Something went wrong, please try again later'));
    }

    /**
     * @OA\Get(
     *     tags={"Doctors"},
     *     path="/api/v1/doctors/{id}",
     *     summary="Get a doctor resource by id",
     *     description="Get a doctor resource by id",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          description="A doctor's identificator",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A doctor has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          ref="#/components/schemas/DoctorResource",
     *                          property="data"
     *                      )
     *                  }
     *              )
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
     *         response=403,
     *         description="Current user has not permissions to do this action",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="This action is unauthorized.."
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
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function show(Doctor $doctor): DoctorResource
    {
        return DoctorResource::make($doctor);
    }

    /**
     * @OA\Patch(
     *     tags={"Doctors"},
     *     path="/api/v1/doctors/{id}",
     *     summary="Update a doctor resource by id",
     *     description="Update a doctor resource by id",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          description="A doctor's identificator",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A doctor has been succesfully updated",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          ref="#/components/schemas/DoctorResource",
     *                          property="data"
     *                      )
     *                  }
     *              )
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
     *         response=403,
     *         description="Current user has not permissions to do this action",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="This action is unauthorized.."
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
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function update(Update $request, Doctor $doctor, StorageService $storage): DoctorResource
    {
        if ($request->has('photo')) {
            $storage->removeFile($doctor->photo);
            $doctor->photo = $storage->saveDoctorsPhoto($request->photo);
        }

        if ($request->has('medical_degree')) {
            $storage->removeFile($doctor->medical_degree);
            $doctor->medical_degree = $storage->saveDoctorsMedicalDegree($request->medical_degree);
        }

        if ($request->has('board_certification')) {
            $storage->removeFile($doctor->board_certification);
            $doctor->board_certification = $storage->saveDoctorsBoardCertification($request->board_certification);
        }

        if ($request->has('password')) {
            $doctor->password = Hash::make($request->password);
        }

        if ($request->has('email')) {
            $doctor->email = $request->email;
            $doctor->email_verified_at = null;
            $doctor->sendEmailVerificationNotification();
        }

        DB::transaction(function () use ($request, $doctor) {
            if ($request->has('language_ids')) {
                $doctor->languages()->detach();
                $doctor->languages()->attach($request->language_ids);
            }

            $doctor->fill(
                $request->only('prefix', 'first_name', 'last_name', 'description', 'region_id')
            )->save();

            Location::updateOrCreate(
                ['doctor_id' => $doctor->id],
                $request->only(['city', 'address', 'postal_code', 'country', 'latitude', 'longitude', 'state'])
            );
        }, 2);

        return new DoctorResource($doctor);
    }

    /**
     * @OA\Get(
     *     tags={"Doctors"},
     *     path="/api/v1/doctors",
     *     summary="Get doctors page",
     *     description="Get doctors page",
     *     @OA\Parameter(
     *          name="first_name",
     *          required=false,
     *          description="Filter doctors by first name",
     *          in="query",
     *          example="David"
     *     ),
     *     @OA\Parameter(
     *          name="last_name",
     *          required=false,
     *          description="Filter doctors by last name",
     *          in="query",
     *          example="Johnson"
     *     ),
     *     @OA\Parameter(
     *          name="region_id",
     *          required=false,
     *          description="Filter doctors by region id",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          required=false,
     *          description="Page number",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Parameter(
     *          name="per_page",
     *          required=false,
     *          description="Items amount on page",
     *          in="query",
     *          example="15"
     *     ),
     *     @OA\Parameter(
     *          name="order_by",
     *          required=false,
     *          description="Order list by a field",
     *          in="query",
     *          example="first_name"
     *     ),
     *     @OA\Parameter(
     *          name="direction",
     *          required=false,
     *          description="Order direction",
     *          in="query",
     *          example="asc"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctors has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          @OA\Items(
     *                              type="object",
     *                              ref="#/components/schemas/DoctorResource"
     *                          ),
     *                          title="Doctors",
     *                          description="Doctors list",
     *                          property="data",
     *                      ),
     *                      @OA\Property(
     *                          @OA\Items(
     *                              properties={
     *                                  @OA\Property(
     *                                      property="first",
     *                                      example="http://online-hautarzt.com/api/v1/doctors?page=1"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="last",
     *                                      example="http://online-hautarzt.com/api/v1/doctors?page=10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="prev",
     *                                      example="http://online-hautarzt.com/api/v1/doctors?page=4"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="next",
     *                                      example="http://online-hautarzt.com/api/v1/doctors?page=6"
     *                                  )
     *                              },
     *                          ),
     *                          property="links"
     *                      ),
     *                      @OA\Property(
     *                          @OA\Items(
     *                              properties={
     *                                  @OA\Property(
     *                                      property="current_page",
     *                                      example="5"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="from",
     *                                      example="9"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="last_page",
     *                                      example="10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="path",
     *                                      example="http://online-hautarzt.com/api/v1/doctors"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="per_page",
     *                                      example="2"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="to",
     *                                      example="10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="total",
     *                                      example="19"
     *                                  ),
     *                              },
     *                          ),
     *                          property="meta"
     *                      )
     *                  }
     *              )
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
    public function index(Request $request): ResourceCollection
    {
        $doctorsQuery = Doctor::query()
            ->whereStatus(Doctor::STATUS_ACTIVATED)
            ->where($request->only(['region_id', 'first_name', 'last_name']))
            ->orderBy(
                $request->query('order_by', 'first_name'),
                $request->query('direction', 'asc'));

        return DoctorResource::collection($doctorsQuery->paginate($request->query('per_page')));
    }
}