<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Doctor\Update as UpdateRequest;
use App\Http\Resources\Doctor as DoctorResource;
use App\Models\Doctor;
use App\Services\StorageService;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Patch(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}",
 *     summary="Update a doctor resource by id",
 *     description="Update a doctor resource by id",
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
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's title ID",
 *                      property="title_id",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's first name",
 *                      property="first_name",
 *                      example="John"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's last name",
 *                      property="last_name",
 *                      example="Carter"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's description",
 *                      property="description",
 *                      example="I am a good doctor"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's phone number",
 *                      property="phone_number",
 *                      example="+3 8(032) 345-34-34"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's old password",
 *                      property="old_password",
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
 *                      format="binary",
 *                      description="A doctor's board certification",
 *                      property="board_certification",
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="A doctor's medical degree",
 *                      property="medical_degree",
 *                  ),
 *                  @OA\Property(
 *                      format="binary",
 *                      description="A doctor's photo",
 *                      property="photo",
 *                  ),
 *                  @OA\Property(
 *                      format="integer",
 *                      description="A doctor's region ID",
 *                      property="regions_id",
 *                  ),
 *                  @OA\Property(
 *                      format="integer",
 *                      description="A doctor's specialization ID",
 *                      property="specialization_id",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's city",
 *                      property="city",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's country",
 *                      property="country",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's address",
 *                      property="address",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's postal code",
 *                      property="postal_code",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="A doctor's state",
 *                      property="state",
 *                  ),
 *                  @OA\Property(
 *                      format="number",
 *                      description="A doctor's latitude",
 *                      property="latitude",
 *                  ),
 *                  @OA\Property(
 *                      format="number",
 *                      description="A doctor's longitude",
 *                      property="longitude",
 *                  ),
 *              )
 *          )
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
class Update extends ApiController
{
    public function __invoke(UpdateRequest $request, Doctor $doctor, StorageService $storage): DoctorResource
    {
        DB::transaction(function () use ($request, $doctor) {
            $doctor->update($request->only(
                'title_id', 'first_name', 'last_name', 'description', 'short_description', 'region_id', 'price_policy_id',
                'specialization_id', 'password', 'photo', 'phone_number', 'medical_degree', 'board_certification'
            ));

            if ($request->has('language_ids')) {
                $doctor->languages()->detach();
                $doctor->languages()->attach($request->language_ids);
            }

            $doctor->location()->updateOrCreate(
                ['model_id' => $doctor->id, 'model_type' => Doctor::class],
                $request->only(['city', 'address', 'postal_code', 'country', 'latitude', 'longitude', 'state'])
            );
        }, 2);

        return DoctorResource::make($doctor->fresh());
    }
}