<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\EnquireMessage;
use App\Models\Enquire;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/messages",
 *     summary="Get enquire messages page",
 *     description="Get enquire messages page",
 *
 *     @OA\Parameter(
 *          name="id",
 *          required=false,
 *          description="Enquire's ID",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Parameter(
 *          name="page",
 *          required=false,
 *          description="Page number",
 *          in="query",
 *          example="1"
 *     ),
 *     @OA\Parameter(
 *          name="per_page",
 *          required=false,
 *          description="Items amount on page",
 *          in="query",
 *          example="15"
 *     ),
 *     @OA\Parameter(
 *          name="order_by",
 *          required=false,
 *          description="Order list by a field. Default: created_at",
 *          in="query",
 *          example="created_at"
 *     ),
 *     @OA\Parameter(
 *          name="direction",
 *          required=false,
 *          description="Order direction. Default: desc. Possible values: asc, desc",
 *          in="query",
 *          example="asc"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Enquire messages has been succesfully received",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          @OA\Items(
 *                              type="object",
 *                              ref="#/components/schemas/EnquireMessageResource"
 *                          ),
 *                          title="Enquire messages",
 *                          description="Enquire messages list",
 *                          property="data",
 *                      ),
 *                      @OA\Property(
 *                          @OA\Items(
 *                              properties={
 *                                  @OA\Property(
 *                                      property="first",
 *                                      example="http://online-hautarzt.com/api/v1/enquire/{1}/messages?page=1"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="last",
 *                                      example="http://online-hautarzt.com/api/v1/enquire/{1}/messages?page=10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="prev",
 *                                      example="http://online-hautarzt.com/api/v1/enquire/{1}/messages?page=4"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="next",
 *                                      example="http://online-hautarzt.com/api/v1/enquire/{1}/messages?page=6"
 *                                  )
 *                              },
 *                          ),
 *                          property="links"
 *                      ),
 *                      @OA\Property(
 *                          @OA\Items(
 *                              properties={
 *                                  @OA\Property(
 *                                      property="current_page",
 *                                      example="5"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="from",
 *                                      example="9"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="last_page",
 *                                      example="10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="path",
 *                                      example="http://online-hautarzt.com/api/v1/enquire/{1}/messages"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="per_page",
 *                                      example="2"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="to",
 *                                      example="10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="total",
 *                                      example="19"
 *                                  ),
 *                              },
 *                          ),
 *                          property="meta"
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
class Messages extends ApiController
{
    public function __invoke(Request $request, Enquire $enquire)
    {
        $messages = $enquire->messages()
            ->orderBy(
                $request->query('order_by', 'created_at'),
                $request->query('direction', 'desc'))
            ->paginate($request->query('per_page'));

        return EnquireMessage::collection($messages);
    }
}