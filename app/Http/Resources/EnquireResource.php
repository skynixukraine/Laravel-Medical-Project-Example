<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Enquire resource",
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
 *     format="string",
 *     title="Phone number",
 *     description="An enquire customer's phone number",
 *     property="phone_number",
 *     example="+38 024-548-58-55"
 * ),
 * @OA\Property(
 *     format="string",
 *     title="E-mail",
 *     description="An enquire customer's e-mail",
 *     property="email",
 *     example="test@gmail.com"
 * ),
 * @OA\Property(
 *     format="string",
 *     title="Conclusion",
 *     description="A doctors's conclusion",
 *     property="conclusion",
 *     example="Everythink will be fine"
 * ),
 * @OA\Property(
 *     format="string",
 *     title="Status",
 *     description="An enquire's status",
 *     property="status",
 *     example="UNREAD"
 * ),
 * @OA\Property(
 *     format="boolean",
 *     title="Is paid",
 *     description="Is an enquire paid",
 *     property="is_paid",
 *     example="true"
 * ),
 */
class EnquireResource extends JsonResource
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
            'status' => $this->status,
            'is_paid' => $this->is_paid,
        ];
    }
}
