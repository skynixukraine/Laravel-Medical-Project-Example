<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Events\ConclusionUpdated;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\UpdateConclusion as UpdateConclusionRequest;
use App\Http\Resources\Enquire as EnquireResource;
use App\Models\Enquire;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Patch(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/update-conclusion",
 *     summary="Update an enquire's conclusion by id",
 *     description="Update an enquire's conclusion by id",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"conclusion"},
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire's conclusion",
 *                      property="conclusion",
 *                      example="test conclusion"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Conclusion has been succesfully updated",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          ref="#/components/schemas/EnquireResource",
 *                          property="data"
 *                      )
 *                  }
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Authorization failed",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="Unauthenticated."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=403,
 *         description="Current user has not permissions to do this action",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="This action is unauthorized."
 *                      ),
 *                  }
 *              )
 *          )
 *      ),
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
class UpdateConclusion extends ApiController
{
    public function __invoke(UpdateConclusionRequest $request, Enquire $enquire)
    {
        Log::info('Start update Conclusion');
        Log::info('Conclusion: ' . $request->conclusion);

        $enquire->update([
            'conclusion' => $request->conclusion,
            'status' => Enquire::STATUS_RESOLVED,
            'conclusion_created_at' => $enquire->conclusion === null ? now() : $enquire->conclusion_created_at
        ]);
        
        event(new ConclusionUpdated($enquire));

        Log::info('Finish update Conclusion');
        
        return EnquireResource::make($enquire);
    }
}