<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Doctor as DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors",
 *     summary="Get doctors page",
 *     description="Get doctors page",
 *     @OA\Parameter(
 *          name="first_name",
 *          required=false,
 *          description="Filter doctors by first name",
 *          in="query",
 *          example="David"
 *     ),
 *     @OA\Parameter(
 *          name="last_name",
 *          required=false,
 *          description="Filter doctors by last name",
 *          in="query",
 *          example="Johnson"
 *     ),
 *     @OA\Parameter(
 *          name="region_id",
 *          required=false,
 *          description="Filter doctors by region id",
 *          in="query",
 *          example="1"
 *     ),
 *     @OA\Parameter(
 *          name="specialization_id",
 *          required=false,
 *          description="Filter doctors by specialization id",
 *          in="query",
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
 *          description="Order list by a field",
 *          in="query",
 *          example="first_name"
 *     ),
 *     @OA\Parameter(
 *          name="direction",
 *          required=false,
 *          description="Order direction",
 *          in="query",
 *          example="asc"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Doctors has been succesfully received",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          @OA\Items(
 *                              type="object",
 *                              ref="#/components/schemas/DoctorResource"
 *                          ),
 *                          title="Doctors",
 *                          description="Doctors list",
 *                          property="data",
 *                      ),
 *                      @OA\Property(
 *                          @OA\Items(
 *                              properties={
 *                                  @OA\Property(
 *                                      property="first",
 *                                      example="http://online-hautarzt.com/api/v1/doctors?page=1"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="last",
 *                                      example="http://online-hautarzt.com/api/v1/doctors?page=10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="prev",
 *                                      example="http://online-hautarzt.com/api/v1/doctors?page=4"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="next",
 *                                      example="http://online-hautarzt.com/api/v1/doctors?page=6"
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
 *                                      example="http://online-hautarzt.com/api/v1/doctors"
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
class Doctors extends ApiController
{
    public function __invoke(Request $request): ResourceCollection
    {
        $doctorsQuery = Doctor::query()
            ->whereStatus(Doctor::STATUS_ACTIVATED)
            ->where($request->only(['region_id', 'specialization_id', 'first_name', 'last_name']))
            ->orderBy(
                $request->query('order_by', 'first_name'),
                $request->query('direction', 'asc'));

        return DoctorResource::collection($doctorsQuery->paginate($request->query('per_page')));
    }
}