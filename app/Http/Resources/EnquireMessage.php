<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="EnquireMessage resource",
 *     schema="EnquireMessageResource",
 *     description="Resource for an enquire message",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="An enquire message's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Content",
 *     description="An enquire message's content",
 *     example="Hallo!",
 *     property="content"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Sender",
 *     description="An enquire message's sender. Can be DOCTOR of PATIENT",
 *     example="DOCTOR",
 *     property="sender"
 * )
 * @OA\Property(
 *     format="int64",
 *     title="An enquire's ID",
 *     description="An enquire's ID related to current message",
 *     example="1",
 *     property="enquire_id"
 * )
 * @OA\Property(
 *     format="int64",
 *     title="An enquire message's ID",
 *     description="An enquire message's ID that current message replied",
 *     example="1",
 *     property="enquire_message_id"
 * )
 * @OA\Property(
 *     ref="#/components/schemas/CarbonResource",
 *     format="object",
 *     title="Created at",
 *     description="Created datetime representation",
 *     property="created_at",
 * ),
 * @OA\Property(
 *     format="object",
 *     title="Updated at",
 *     description="Last updated datetime representation",
 *     property="updated_at",
 *     ref="#/components/schemas/CarbonResource",
 * ),
 */
class EnquireMessage extends JsonResource
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
            'content' => $this->content,
            'sender' => $this->sender,
            'enquire_id' => $this->enquire_id,
            'enquire_message_id' => $this->enquire_message_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
