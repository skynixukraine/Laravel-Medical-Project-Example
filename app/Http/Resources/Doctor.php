<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CarbonResource",
 *     title="Carbon resource",
 *     description="Resource for datetime representation",
 *     properties={
 *          @OA\Property(
 *              format="string",
 *              property="date",
 *              example="2019-12-11 12:25:00.000000"
 *          ),
 *          @OA\Property(
 *              format="int64",
 *              property="timezone_type",
 *              example=3
 *          ),
 *          @OA\Property(
 *              format="string",
 *              property="timezone",
 *              example="Europe/Berlin"
 *          ),
 *     }
 * ),
 * @OA\Schema(
 *     title="Doctor resource",
 *     schema="DoctorResource",
 *     description="Resource for a doctor representation",
 *     properties={
 *          @OA\Property(
 *              format="int64",
 *              title="ID",
 *              description="A doctors's identificator",
 *              example="5",
 *              property="id"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="Photo",
 *              description="Photo",
 *              property="photo",
 *              example="public/doctors/2019-12/john-sanchess89.jpeg"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="Prefix",
 *              description="A doctor's prefix",
 *              property="prefix",
 *              example="Dr. med."
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="First name",
 *              description="A doctor's first name",
 *              property="first_name",
 *              example="Davide"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="Last name",
 *              description="A doctor's last name",
 *              property="last_name",
 *              example="Donghi"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="E-mail",
 *              description="A doctor's e-mail",
 *              property="email",
 *              example="test@gmail.com"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="Phone number",
 *              description="An doctor's phone number",
 *              property="phone_number",
 *              example="+38 024-548-58-55"
 *          ),
 *          @OA\Property(
 *              format="string",
 *              title="Desctiption",
 *              description="A doctor's description",
 *              property="description",
 *              example="I am a good doctor"
 *          ),
 *          @OA\Property(
 *              format="boolean",
 *              title="Status",
 *              description="A doctor's status",
 *              property="status",
 *              example="CREATED"
 *          ),
 *          @OA\Property(
 *              ref="#/components/schemas/CarbonResource",
 *              format="object",
 *              title="Created at",
 *              description="Created datetime representation",
 *              property="created_at",
 *          ),
 *          @OA\Property(
 *              format="object",
 *              title="Updated at",
 *              description="Last updated datetime representation",
 *              property="updated_at",
 *              ref="#/components/schemas/CarbonResource",
 *          ),
 *          @OA\Property(
 *              ref="#/components/schemas/RegionResource",
 *              property="region"
 *          ),
 *          @OA\Property(
 *              ref="#/components/schemas/SpecializationResource",
 *              property="specialization"
 *          ),
 *          @OA\Property(
 *              ref="#/components/schemas/LocationResource",
 *              property="location"
 *          ),
 *          @OA\Property(
 *              @OA\Items(
 *                  type="object",
 *                  ref="#/components/schemas/LanguageResource"
 *              ),
 *              title="Languages",
 *              description="Doctor's languages",
 *              property="languages",
 *          ),
 *     }
 * )
 */
class Doctor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'photo' => $this->photo,
            'board_certification' => $this->board_certification,
            'medical_degree' => $this->medical_degree,
            'prefix' => $this->prefix,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at,
            'region' => new Region($this->region),
            'specialization' => new Specialization($this->specialization),
            'location' => new Location($this->location),
            'languages' => Language::collection($this->languages),
            'enquire_price' => Setting::fetchValue('display_enquire_price'),
        ];
    }
}
