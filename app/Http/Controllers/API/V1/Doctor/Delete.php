<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Events\DoctorDeleted;
use App\Http\Controllers\API\V1\ApiController;
use App\Models\Doctor;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stripe\OAuth;
use App\Http\Requests\Doctor\Delete as DeleteRequest;

/**
 * @OA\Patch(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/delete",
 *     summary="Close a doctor's account",
 *     description="Close a doctor's account",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="A doctor's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"password"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="Password",
 *                      description="A doctor's password",
 *                      property="password",
 *                      example="12345678"
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(response=200, description="An account has been succesfully deleted"),
 *     @OA\Response(response=304, description="An account already closed"),
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
 *                          example="This action is unauthorized."
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
 *                              property="password",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The password is invalid."
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
class Delete extends ApiController
{
    public function __invoke(DeleteRequest $request, Doctor $doctor): void
    {
        throw_if(!Hash::check($request->password, $doctor->password), ValidationException::withMessages([
            'password' => __('The password is invalid.'),
        ]));

        DB::transaction(function () use ($doctor) {

            $doctor->removeBoardCertificationFile();
            $doctor->removeMedicalDegreeFile();
            $doctor->removePhotoFile();

            if ($doctor->stripe_account_id) {

                OAuth::deauthorize([
                    'client_id'      => config('services.stripe.key'),
                    'stripe_user_id' => $doctor->stripe_account_id,
                ]);
            }

            event(new DoctorDeleted($doctor));

            $doctor->delete();
        });
    }
}