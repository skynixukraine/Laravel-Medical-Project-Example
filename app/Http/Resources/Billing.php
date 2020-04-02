<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Billing resource",
 *     schema="BillingResource",
 *     description="Resource for an billing representation",
 * )
 * @OA\Property(
 *     format="int64",
 *     title="ID",
 *     description="An enquire's identificator",
 *     example="5",
 *     property="id"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Anount",
 *     description="A billing amount",
 *     property="amount",
 *     example="20.15"
 * )
 * @OA\Property(
 *     format="string",
 *     title="Currency",
 *     description="A billing's currency",
 *     property="currency",
 *     example="usd"
 * ),
 * @OA\Property(
 *     ref="#/components/schemas/EnquireResource",
 *     property="enquire"
 * ),
 */
class Billing extends JsonResource
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
            'amount' => money_format('%.2n', $this->amount / 100),
            'currency' => $this->currency,
            'enquire' => Enquire::make($this->enquire)
        ];
    }
}