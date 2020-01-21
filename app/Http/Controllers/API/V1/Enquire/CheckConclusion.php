<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Enquire;

/**
 * @OA\Get(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/check-conclusion",
 *     summary="Check if enquire's conclusion is abailable",
 *     description="Check if enquire's conclusion is abailable. The reason property can has two values. 1 - if conclusion has not been provided yet, 2 - if conclusion already expired and not available",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Conclusion availability has been succesfully checked",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          type="boolean",
 *                          property="available",
 *                          example=false
 *                      ),
 *                      @OA\Property(
 *                          type="int64",
 *                          property="reason",
 *                          example=1,
 *                      ),
 *                  }
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Enquiere not found",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="No query results for model [App\Models\Enquire]."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
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
class CheckConclusion extends ApiController
{
    public function __invoke(Enquire $enquire)
    {
        $response['available'] = $enquire->conclusion_created_at && $enquire->conclusion_created_at->addWeek(6)->greaterThanOrEqualTo(now());

        if (!$response['available']) {
            $response['reason'] = !$enquire->conclusion_created_at ? 1 : 2;
        }

        return $response;
    }
}