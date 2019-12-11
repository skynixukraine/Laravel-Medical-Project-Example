<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Location resource",
 *     description="Resource for a location representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A region's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's address",
 *     description="A location's address",
 *     property="address",
 *     example="Some address"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A location's name",
 *     description="A location's name",
 *     property="name",
 *     example="Nevada",
 *     nullable=true
 * )
 * @OA\Property(
 *     format="double",
 *     title="A location's lat",
 *     description="A location's lat",
 *     property="lat",
 *     example=5.123,
 *     nullable=true
 * )
 * @OA\Property(
 *     format="double",
 *     title="A location's lng",
 *     description="A location's lng",
 *     property="lng",
 *     example=8.123,
 *     nullable=true
 * )
 */
class LocationResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'type' => $this->type,
        ];
    }
}
