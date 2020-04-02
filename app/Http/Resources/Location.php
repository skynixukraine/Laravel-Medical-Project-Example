<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Location resource",
 *     schema="LocationResource",
 *     description="Resource for a location representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A locations's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's country",
 *     description="A location's country",
 *     property="country",
 *     example="USA"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's city",
 *     description="A location's city",
 *     property="city",
 *     example="New York",
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's state",
 *     description="A location's state",
 *     property="state",
 *     example="New York",
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's postal code",
 *     description="A location's postal code",
 *     property="postal_code",
 *     example="12345",
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's address",
 *     description="A location's address",
 *     property="address",
 *     example="address",
 * )
 * @OA\Property(
 *     format="double",
 *     title="A location's latitude",
 *     description="A location's latitude",
 *     property="latitude",
 *     example=5.123,
 *     nullable=true
 * )
 * @OA\Property(
 *     format="double",
 *     title="A location's longitude",
 *     description="A location's longitude",
 *     property="longitude",
 *     example=8.123,
 *     nullable=true
 * )
 */
class Location extends JsonResource
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
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
