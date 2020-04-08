<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Enquire resource",
 *     schema="EnquireResource",
 *     description="Resource for an enquire representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="An enquire's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="First name",
 *     description="An enquire customer's first name",
 *     property="first_name",
 *     example="Davide"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Last name",
 *     description="An enquire customers's last name",
 *     property="last_name",
 *     example="Donghi"
 * ),
 * @OA\Property(
 *     format="string",
 *     title="Gender",
 *     description="An enquire customer's gender",
 *     property="gender",
 *     example="MALE",
 * )
 * @OA\Property(
 *     ref="#/components/schemas/CarbonResource",
 *     format="object",
 *     title="Date of birth",
 *     description="Date of birth representation",
 *     property="date_of_birth",
 * )
 * @OA\Property(
 *     ref="#/components/schemas/CarbonResource",
 *     format="object",
 *     title="Created at",
 *     description="Created datetime representation",
 *     property="created_at",
 * )
 * @OA\Property(
 *     format="object",
 *     title="Updated at",
 *     description="Last updated datetime representation",
 *     property="updated_at",
 *     ref="#/components/schemas/CarbonResource",
 * )
 * @OA\Property(
 *     format="object",
 *     title="Last contacted at",
 *     description="Last contacted datetime representation",
 *     property="last_contacted_at",
 *     ref="#/components/schemas/CarbonResource",
 * )
 * @OA\Property(
 *     format="string",
 *     title="Phone number",
 *     description="An enquire customer's phone number",
 *     property="phone_number",
 *     example="+38 024-548-58-55"
 * )
 * @OA\Property(
 *     format="string",
 *     title="E-mail",
 *     description="An enquire customer's e-mail",
 *     property="email",
 *     example="test@gmail.com"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Conclusion",
 *     description="A doctors's conclusion",
 *     property="conclusion",
 *     example="Everythink will be fine"
 * )
 * @OA\Property(
 *     format="object",
 *     title="Conclusion created at",
 *     description="Conclusion created datetime representation",
 *     property="conclusion_created_at",
 *     ref="#/components/schemas/CarbonResource",
 * )
 * @OA\Property(
 *     format="string",
 *     title="Status",
 *     description="An enquire's status",
 *     property="status",
 *     example="UNREAD"
 * )
 * @OA\Property(
 *     format="boolean",
 *     title="Is paid",
 *     description="Is an enquire paid",
 *     property="is_paid",
 *     example="true"
 * ),
 * @OA\Property(
 *     format="boolean",
 *     title="Is seen",
 *     description="Is an enquire seen",
 *     property="is_seen",
 *     example="true"
 * ),
 * @OA\Property(
 *     ref="#/components/schemas/LocationResource",
 *     property="location"
 * ),
 * @OA\Property(
 *     @OA\Items(
 *          type="object",
 *          ref="#/components/schemas/EnquireAnswerResource"
 *     ),
 *     title="Answers",
 *     description="An enquire's answers",
 *     property="answers",
 * ),
 */
class Enquire extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'conclusion' => $this->conclusion,
            'conclusion_created_at' => $this->conclusion_created_at,
            'status' => __($this->status),
            'is_seen' => $this->is_seen,
            'is_paid' => $this->billing()->exists(),
            'location' => Location::make($this->location),
            'answers' => EnquireAnswer::collection($this->whenLoaded('answers')),
            'last_contacted_at' => $this->last_contacted_a,
            'is_verified_email' => $this->hasVerifiedEmail(),
            'payment_status' => $this->payment_status,
            'price' => Setting::fetchValue('enquire_total_price', 0) * 100,
            'currency' => Setting::fetchValue('enquire_price_currency', 'usd'),
        ];
    }
}
