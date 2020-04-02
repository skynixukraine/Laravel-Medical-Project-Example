<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Region resource",
 *     schema="RegionResource",
 *     description="Resource for a region representation",
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
 *     title="A region's name",
 *     description="A region's name",
 *     property="name",
 *     example="Nevada"
 * )
 */
class Region extends JsonResource
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
        ];
    }
}
