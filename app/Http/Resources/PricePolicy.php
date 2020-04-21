<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


class PricePolicy extends JsonResource
{
    /**
     * @OA\Schema(
     *     title="Price policy resource",
     *     schema="PricePolicyResource",
     *     description="Resource for an price policy representation",
     * )
     * @OA\Property(
     *     format="int64",
     *     title="ID",
     *     description="An price policy identificator",
     *     example="5",
     *     property="id"
     * )
     * @OA\Property(
     *     format="string",
     *     title="Description",
     *     description="A price policy description",
     *     property="Description",
     *     example="First plan"
     * )
     * @OA\Property(
     *     format="string",
     *     title="Enquire display price",
     *     description="A price policy enquire display price",
     *     property="enquire_display_price",
     *     example="19.00$"
     * ),
     * @OA\Property(
     *     format="string",
     *     title="Currency",
     *     description="A price policy currency",
     *     property="currency",
     *     example="usd"
     * ),
     * @OA\Property(
     *     format="int64",
     *     title="Enquire total price",
     *     description="A price policy enquire_total_price",
     *     property="enquire_total_price",
     *     example="1900"
     * ),
     * @OA\Property(
     *     format="int64",
     *     title="Enquire admins fee",
     *     description="A price policy enquire admins fee",
     *     property="enquire_admins_fee",
     *     example="650"
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'enquire_display_price' => $this->enquire_display_price,
            'enquire_total_price' => sprintf('%.2f', $this->enquire_total_price / 100),
            'enquire_admins_fee' => sprintf('%.2f', $this->enquire_admins_fee / 100),
            'currency' => $this->currency,
        ];
    }
}
