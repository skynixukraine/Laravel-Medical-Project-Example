<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="EnquireMessageAttachment resource",
 *     schema="EnquireMessageAttachment",
 *     description="Resource for an enquire message attachment representation",
 *     properties={
 *         @OA\Property(
 *             format="integer",
 *             property="id",
 *             example="1"
 *         ),
 *         @OA\Property(
 *             format="string",
 *             property="url",
 *             example="http://ohn/storage/enquire_messages/attachments/2020/01/28/lyspbnkojm0vvigl0fmf1hdnwu51mm8jimzf6jvegif.gif",
 *         ),
 *         @OA\Property(
 *             ref="#/components/schemas/CarbonResource",
 *             format="object",
 *             property="craeted_at",
 *         ),
 *         @OA\Property(
 *             ref="#/components/schemas/CarbonResource",
 *             format="object",
 *             property="updated_at",
 *         ),
 *     }
 * )
 */
class EnquireMessageAttachment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->path ? asset($this->path) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
