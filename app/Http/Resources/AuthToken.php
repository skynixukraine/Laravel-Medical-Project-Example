<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="AuthToken resource",
 *     schema="AuthTokenResource",
 *     description="Resource for an authorization token representation",
 *     properties={
 *         @OA\Property(
 *             format="integer",
 *             property="id",
 *             example="1"
 *         ),
 *         @OA\Property(
 *             format="string",
 *             property="access_token",
 *             example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ3ZGY5ZDdkYmY4ZmM1Mz",
 *         ),
 *         @OA\Property(
 *             ref="#/components/schemas/CarbonResource",
 *             format="object",
 *             property="expires_at",
 *         ),
 *     }
 * )
 */
class AuthToken extends JsonResource
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
            'id' => $this->token->user_id,
            'access_token' => $this->accessToken,
            'expires_at' => $this->token->expires_at,
        ];
    }
}
