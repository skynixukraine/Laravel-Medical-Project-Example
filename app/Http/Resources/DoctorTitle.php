<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="DoctorTitle resource",
 *     schema="DoctorTitleResource",
 *     description="DoctorTitle for a region representation",
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
 *     title="A title's name",
 *     description="A title's name",
 *     property="name",
 *     example="Dr."
 * )
 */
class DoctorTitle extends JsonResource
{
    /**
 * Transform the resource into an array.
 *
 * @param  Request  $request
 * @return array
 */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
