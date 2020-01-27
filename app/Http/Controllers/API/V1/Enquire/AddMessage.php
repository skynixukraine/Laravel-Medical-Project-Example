<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\AddMessage as AddMessageRequest;
use App\Http\Resources\EnquireMessage as EnquireMessageResource;
use App\Models\Enquire;
use App\Models\EnquireMessage;

/**
 * @OA\Patch(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/add-message",
 *     summary="Add a new enquire message to enquire",
 *     description="Add a new enquire message to enquire",
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
 *                  required={"content"},
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire message's content",
 *                      property="content",
 *                      example="test content"
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
 *                          ref="#/components/schemas/EnquireMessageResource",
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
class AddMessage extends ApiController
{
    public function __invoke(AddMessageRequest $request, Enquire $enquire)
    {
        return EnquireMessageResource::make($enquire->messages()->create([
            'content' => $request->input('content'),
            'sender' => EnquireMessage::SENDER_DOCTOR,
        ]));
    }
}