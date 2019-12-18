<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Events\DoctorRegistered;
use App\Http\Requests\Doctor\Login;
use App\Http\Requests\Doctor\Register;
use App\Http\Requests\Doctor\SendResetLink;
use App\Http\Requests\Doctor\UpdatePassword;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Location;
use App\Services\StorageService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
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
     *                  required={"photo", "first_name", "last_name", "description", "prefix", "email", "marker_address",
     *                          "region_id", "language_ids", "password", "password_confirmation", "recaptcha",
     *                          "country", "city", "state", "postal_code", "address", "latitude", "longitude"},
     *                  @OA\Property(
     *                      format="binary",
     *                      description="A doctor's photo",
     *                      property="photo",
     *                  ),
     *                   @OA\Property(
     *                      format="string",
     *                      description="A doctor's prefix",
     *                      property="prefix",
     *                      example="Dr."
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A doctor's first name",
     *                      property="first_name",
     *                      example="Davide"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A doctor's last name",
     *                      property="last_name",
     *                      example="Donghi"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A doctor's description",
     *                      property="description",
     *                      example="I am a good doctor"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A doctor's e-mail",
     *                      property="email",
     *                      example="doctor@gmail.com"
     *                  ),
     *                  @OA\Property(
     *                      format="int64",
     *                      description="A doctor's region",
     *                      property="region_id",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      format="array",
     *                      description="A doctor's languages",
     *                      property="language_ids",
     *                      items="int64"
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
     *                      format="string",
     *                      description="A location's country",
     *                      property="country",
     *                      example="USA"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A location's city",
     *                      property="city",
     *                      example="New York",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A location's state",
     *                      property="state",
     *                      example="New York",
     *                  ),
     *                  @OA\Property(
     *                      format="int64",
     *                      description="A location's postal code",
     *                      property="postal_code",
     *                      example="12345",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      description="A location's address",
     *                      property="address",
     *                      example="address",
     *                  ),
     *                  @OA\Property(
     *                      format="double",
     *                      description="A location's latitude",
     *                      property="latitude",
     *                      example=5.123,
     *                  ),
     *                  @OA\Property(
     *                      format="double",
     *                      description="A location's longitude",
     *                      property="longitude",
     *                      example=8.123,
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
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
     *                              property="prefix",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The prefix field is required."
     *                              ),
     *                          ),
     *                          @OA\Property(
     *                              property="first_name",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The first name field is required."
     *                              ),
     *                          ),
     *                          @OA\Property(
     *                              property="last_name",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The last name field is required."
     *                              ),
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The description field is required."
     *                              ),
     *                          ),
     *                          @OA\Property(
     *                              property="region_id",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The region id field is required."
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
     *                              property="address",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The address field is required."
     *                              ),
     *                          ),
     *                          @OA\Property(
     *                              property="language_ids",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The language ids field is required."
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
    public function register(Register $request, StorageService $storageService): DoctorResource
    {
        $location = Location::firstOrCreate(
            $request->only(['address', 'city', 'state', 'postal_code', 'country', 'latitude', 'longitude'])
        );

        $doctor = new Doctor(
            $request->only(['prefix', 'first_name', 'last_name', 'description', 'region_id', 'email'])
        );

        $doctor->location_id = $location->id;
        $doctor->password = Hash::make($request->password);
        $doctor->is_active = false;

        $storageService->saveDoctorPhoto($doctor, $request->photo);

        $doctor->save();

        $doctor->languages()->attach($request->language_ids);

        event(new DoctorRegistered($doctor));

        return new DoctorResource($doctor);
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
        $doctor = Doctor::where(
            [
                'email' => $request->input('email'),
                'is_active' => true,
            ]
        )->first();

        if (!$doctor || Hash::check($doctor, $doctor->password)) {
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
}