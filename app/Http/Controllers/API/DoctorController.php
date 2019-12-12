<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Events\DoctorRegistered;
use App\Http\Requests\Doctor\RegisterDoctor;
use App\Http\Requests\Doctor\SendResetLink;
use App\Http\Requests\Doctor\UpdatePassword;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Location;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use OpenApi\Annotations as OA;

class DoctorController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new doctor",
     *     description="Register a new doctor",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"photo", "first_name", "last_name", "description", "prefix", "email", "marker_address",
     *                          "region_id", "language_ids", "password", "password_confirmation", "recaptcha"},
     *                  @OA\Property(
     *                      format="binary",
     *                      title="Photo",
     *                      description="A doctor's photo",
     *                      property="photo",
     *                  ),
     *                   @OA\Property(
     *                      format="string",
     *                      title="Prefix",
     *                      description="A doctor's prefix",
     *                      property="prefix",
     *                      example="Dr."
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="First name",
     *                      description="A doctor's first name",
     *                      property="first_name",
     *                      example="Davide"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Last name",
     *                      description="A doctor's last name",
     *                      property="last_name",
     *                      example="Donghi"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Desctiption",
     *                      description="A doctor's description",
     *                      property="description",
     *                      example="I am a good doctor"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="E-mail",
     *                      description="A doctor's e-mail",
     *                      property="email",
     *                      example="doctor@gmail.com"
     *                  ),
     *                  @OA\Property(
     *                      format="int64",
     *                      title="Region",
     *                      description="A doctor's region",
     *                      property="region_id",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      format="array",
     *                      title="Languages",
     *                      description="A doctor's languages",
     *                      property="language_ids",
     *                      items="int64"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Password",
     *                      description="A doctor's password",
     *                      property="password",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Password confirmation",
     *                      description="A doctor's password confirmation",
     *                      property="password_confirmation",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Recaptcha",
     *                      description="Recaptcha value",
     *                      property="recaptcha",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Marker address",
     *                      description="A doctor's marker address",
     *                      property="marker_address",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Marker name",
     *                      description="A doctor's marker name",
     *                      property="marker_name",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Marker lat",
     *                      description="A doctor's marker lat",
     *                      property="marker_lat",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Marker lng",
     *                      description="A doctor's marker lng",
     *                      property="marker_lng",
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Marker type",
     *                      description="A doctor's marker type",
     *                      property="marker_type",
     *                  ),
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
     *                              property="marker_address",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="The marker address field is required."
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
    public function register(RegisterDoctor $request): DoctorResource
    {
        $location = Location::create(
            [
                'name' => $request->input('marker_name'),
                'address' => $request->input('marker_address'),
                'lat' => $request->input('marker_lat'),
                'lng' => $request->input('marker_lng'),
                'type' => $request->input('marker_type'),
            ]
        );

        $doctor = new Doctor(
            [
                'prefix' => $request->prefix,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'description' => $request->description,
                'region_id' => $request->region_id,
                'location_id' => $location->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => false,
            ]
        );

        $doctor->uploadPhoto($request->file('photo'));

        $doctor->save();

        $doctor->languages()->attach($request->language_ids);

        event(new DoctorRegistered($doctor));

        return new DoctorResource($doctor);
    }

    /**
     * @OA\Post(
     *     path="/api/send-reset-link",
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
     *                      description="Recaptcha value",
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

        if ($response !== Password::RESET_LINK_SENT) {
            abort(500, __('Something went wrong, please try again later'));
        }
    }

    public function login(Request $request)
    {
        $http = new Client;

        $response = $http->post(
            'http://ohn/oauth/token',
            [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('CLIENT_ID'),
                    'client_secret' => env('CLIENT_SECRET'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]
        );

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @OA\Post(
     *     path="/api/send-reset-link",
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
     *                      description="Recaptcha value",
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

        if ($response !== Password::PASSWORD_RESET) {
            abort(500, __('Something went wrong, please try again later'));
        }
    }
}