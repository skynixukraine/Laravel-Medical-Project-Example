<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Facades\Storage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage as BaseStorage;
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
 *              ref="#/components/schemas/DoctorTitleResource",
 *              property="title"
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
 *              title="Short description",
 *              description="A doctor's short description",
 *              property="short_description",
 *              example="I am a good doctor"
 *          ),
 *          @OA\Property(
 *              format="string",
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
 *              ref="#/components/schemas/PricePolicyResource",
 *              property="price_policy"
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
 *          @OA\Property(
 *              format="boolean",
 *              title="Can be approved",
 *              description="Determine if a doctor can be approved. The property available only if status property equals 'CREATED'",
 *              property="can_be_approved",
 *              example=true
 *          ),
 *     }
 * )
 */
class Doctor extends JsonResource
{
    private $withMedicalDegree = false;
    private $withBoardCertification = false;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if (Auth::id() === $this->id) {
            $this->withMedicalDegree = true;
            $this->withBoardCertification = true;
        }
    }

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
            'photo' => $this->photo ? BaseStorage::temporaryUrl($this->photo, now()->addMinutes(5)) : null,
            'board_certification' => $this->mergeWhen($this->withMedicalDegree,
                $this->board_certification ? Storage::getDecryptedBase64Uri($this->board_certification) : null),
            'medical_degree' => $this->mergeWhen($this->withBoardCertification,
                $this->medical_degree ? Storage::getDecryptedBase64Uri($this->medical_degree) : null),
            'title' => $this->title,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'short_description' => $this->short_description,
            'status' => __($this->status),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at,
            'region' => new Region($this->region),
            'specialization' => new Specialization($this->specialization),
            'location' => new Location($this->location),
            'languages' => Language::collection($this->languages),
            'enquire_price' => $this->pricePolicy->enquire_display_price,
            'price' => $this->pricePolicy->enquire_total_price,
            'currency' => $this->pricePolicy->currency,
            'price_policy' => new PricePolicy($this->pricePolicy),
            'can_be_approved' => $this->when($this->status === \App\Models\Doctor::STATUS_CREATED, $this->canBeApproved()),
        ];
    }
}
