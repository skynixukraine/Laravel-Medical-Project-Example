<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Enquire;

/**
 * @OA\Get(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/conclusion-status",
 *     summary="Check if enquire's conclusion is abailable",
 *     description="Check if enquire's conclusion is abailable. Possible statuses. 0 - if conclusion was expired, 1 - if conclusion available, 2 - if conclusion has not been provided",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Conclusion status has been succesfully checked",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          type="int64",
 *                          property="status",
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
class ConclusionStatus extends ApiController
{
    public function __invoke(Enquire $enquire)
    {
        return ['status' => !$enquire->conclusion_created_at ? 2 : (int) $enquire->isConclusionExpired()];
    }
}