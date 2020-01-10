<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Message option resource",
 *     description="Resource for a message option representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A message option's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message option's value",
 *     description="A message option's value",
 *     property="value",
 *     example="value"
 * )
 * @OA\Property(
 *     ref="#/components/schemas/MessageResource",
 *     property="message"
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="Next message identificator",
 *     example="5",
 *     property="next_message_id"
 * )
 */
class MessageOption extends JsonResource
{
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
            'value' => $this->value,
            'next_message_id' => $this->next_message_id,
        ];
    }
}
