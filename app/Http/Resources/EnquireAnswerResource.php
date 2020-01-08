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
 *     description="An enquire answers's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     ref="#/components/schemas/MessageResource",
 *     property="message",
 *     description="An enquire answers's message",
 * )
 * @OA\Property(
 *     ref="#/components/schemas/MessageOptionResource",
 *     property="message_option",
 *     description="An enquire answers's selected message option",
 * )
 * @OA\Property(
 *     format="string",
 *     title="Value",
 *     description="An enquire answers's value",
 *     example="Test",
 *     property="value"
 * )
 */
class EnquireAnswerResource extends JsonResource
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
            'message' => MessageResource::make($this->message),
            'message_option' => MessageOptionResource::make($this->message_option),
            'value' => $this->value
        ];
    }
}
