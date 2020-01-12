<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Language resource",
 *     schema="LanguageResource",
 *     description="Resource for a language representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A language's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A language's code",
 *     description="A language's code",
 *     property="code",
 *     example="en"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A language's name",
 *     description="A language's name",
 *     property="name",
 *     example="English"
 * )
 */
class Language extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
