<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Payment methods resource",
 *     schema="PaymentMethodResource",
 *     description="Resource for an payment methods representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="An payment method's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Name",
 *     description="A payment method name",
 *     property="name",
 *     example="20.15"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Title",
 *     description="A payment method title",
 *     property="currency",
 *     example="usd"
 * ),
 * @OA\Property(
 *     format="string",
 *     title="Country",
 *     description="A payment method country",
 *     property="country",
 *     example="usd"
 * ),
 * @OA\Property(
 *     ref="#/components/schemas/EnquireResource",
 *     property="enquire"
 * ),
 */
class PaymentMethods extends JsonResource
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
            'name' => $this->name,
            'title' => $this->title,
            'country' => $this->country
        ];
    }
}