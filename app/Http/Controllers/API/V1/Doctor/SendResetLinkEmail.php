<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Doctor\SendResetLink;
use Illuminate\Support\Facades\Password;
use OpenApi\Annotations as OA;

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
class SendResetLinkEmail extends ApiController
{
    public function __invoke(SendResetLink $request): void
    {
        $response = Password::broker('doctors')->sendResetLink($request->only('email'));

        abort_if($response !== Password::RESET_LINK_SENT, 500, __('Something went wrong, please try again later'));
    }
}