<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\PricePolicy;
use OpenApi\Annotations as OA;

class PricePolicyController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"PricePolicy"},
     *     path="/api/v1/pricing-policies",
     *     summary="Get all pricing policies",
     *     description="Get all pricing policies",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          property="data",
     *                          @OA\Items(
     *                              type="object",
     *                              ref="#/components/schemas/PricePolicyResource"
     *                          ),
     *                      )
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal technical error was happened",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="Something went wrong, please try again later."
     *                      ),
     *                  }
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        $pricingPolicies = \App\Models\PricePolicy::query()->get();
        return PricePolicy::collection($pricingPolicies);
    }
}