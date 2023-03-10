<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Message resource",
 *     schema="MessageResource",
 *     description="Resource for a message representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="A message's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message's title",
 *     description="A message's title",
 *     property="title",
 *     example="How are you?"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message's content",
 *     description="A message's content",
 *     property="content",
 *     example="How are you?"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message's questioner",
 *     description="A message's questioner",
 *     property="questioner",
 *     example="Doctor"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message's type",
 *     description="A message's type",
 *     property="type",
 *     example="simple"
 * )
 * @OA\Property(
 *     format="string",
 *     title="A message's button",
 *     description="A message's button",
 *     property="button",
 *     example="Click here!"
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="Next message identificator",
 *     example="5",
 *     property="next_message_id"
 * )
 * @OA\Property(
 *     @OA\Items(
 *         type="object",
 *         ref="#/components/schemas/MessageOptionResource"
 *     ),
 *     title="Options",
 *     description="Message's options",
 *     property="options",
 * ),
 */
class Message extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'questioner' => $this->questioner,
            'type' => $this->type,
            'button' => $this->button,
            'next_message_id' => $this->next_message_id,
            'options' => MessageOption::collection($this->options),
        ];
    }
}
