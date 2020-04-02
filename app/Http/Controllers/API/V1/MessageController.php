<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\Message as MessageResource;
use App\Models\Message;

class MessageController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"Messages"},
     *     path="/api/v1/messages/first",
     *     summary="Get first message",
     *     description="Get first message",
     *     @OA\Response(
     *         response=200,
     *         description="First message has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          ref="#/components/schemas/MessageResource",
     *                          property="data"
     *                      )
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function first(): MessageResource
    {
        return MessageResource::make(Message::whereIsFirst(true)->firstOrFail());
    }

    /**
     * @OA\Get(
     *     tags={"Messages"},
     *     path="/api/v1/messages/{id}",
     *     summary="Get a message resource by id",
     *     description="Get a message resource by id",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          description="A message's identificator",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A message has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          ref="#/components/schemas/MessageResource",
     *                          property="data"
     *                      )
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          format="string",
     *                          property="message",
     *                          example="No query results for model [App\Models\Doctor]."
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
    public function show(Message $message): MessageResource
    {
        return MessageResource::make($message);
    }
}