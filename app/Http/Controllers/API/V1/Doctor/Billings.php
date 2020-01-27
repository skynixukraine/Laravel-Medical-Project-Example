<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Billing;
use App\Models\Doctor;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/billings",
 *     summary="Get billings page",
 *     description="Get billings page",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="A doctor's id",
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
 *     @OA\Response(
 *         response=200,
 *         description="Billings has been succesfully received",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          @OA\Items(
 *                              type="object",
 *                              ref="#/components/schemas/EnquireResource"
 *                          ),
 *                          title="Enquires",
 *                          description="Enquires list",
 *                          property="data",
 *                      ),
 *                      @OA\Property(
 *                          @OA\Items(
 *                              properties={
 *                                  @OA\Property(
 *                                      property="first",
 *                                      example="http://online-hautarzt.com/api/v1/enquires?page=1"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="last",
 *                                      example="http://online-hautarzt.com/api/v1/enquires?page=10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="prev",
 *                                      example="http://online-hautarzt.com/api/v1/enquires?page=4"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="next",
 *                                      example="http://online-hautarzt.com/api/v1/enquires?page=6"
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
 *                                      example="http://online-hautarzt.com/api/v1/enquires"
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
class Billings extends ApiController
{
    public function __invoke(Request $request, Doctor $doctor)
    {
        return Billing::collection(
            $doctor->billings()
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('per_page', 50))
        );
    }
}