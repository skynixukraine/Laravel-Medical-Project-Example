<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Specialization resource",
 *     schema="SpecializationResource",
 *     description="Resource for a specialization representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A specialization's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A specialization's name",
 *     description="A specialization's name",
 *     property="name",
 *     example="Dermatologist"
 * )
 */
class Specialization extends JsonResource
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
