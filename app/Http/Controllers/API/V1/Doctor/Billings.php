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
 *     @OA\Parameter(
 *          name="search",
 *          required=false,
 *          description="Search string",
 *          in="query",
 *          example="John"
 *     ),
 *     @OA\Parameter(
 *          name="order_field",
 *          required=false,
 *          description="Order field. Possible values: id, enquire_id, created_at",
 *          in="query",
 *          example="created_at"
 *     ),
 *     @OA\Parameter(
 *          name="order_direction",
 *          required=false,
 *          description="Order direction. Possible values: asc, desc",
 *          in="query",
 *          example="asc"
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
 *                              ref="#/components/schemas/BillingResource"
 *                          ),
 *                          title="Billings",
 *                          description="Billings list",
 *                          property="data",
 *                      ),
 *                      @OA\Property(
 *                          @OA\Items(
 *                              properties={
 *                                  @OA\Property(
 *                                      property="first",
 *                                      example="http://online-hautarzt.com/api/v1/billings?page=1"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="last",
 *                                      example="http://online-hautarzt.com/api/v1/billings?page=10"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="prev",
 *                                      example="http://online-hautarzt.com/api/v1/billings?page=4"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="next",
 *                                      example="http://online-hautarzt.com/api/v1/billings?page=6"
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
 *                                      example="http://online-hautarzt.com/api/v1/billings"
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
class Billings extends ApiController
{
    public function __invoke(Request $request, Doctor $doctor)
    {
        $billings = $doctor->billings();

        if ($request->has('search')) {
            $billings->where(function ($query) use ($request) {
                $query->where('enquires.first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('enquires.last_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $sortableFields = collect(['id', 'enquire_id', 'created_at']);

        $request->has('order_field') && $sortableFields->contains($request->order_field)
            ? $billings->orderBy($request->order_field, $request->query('order_direction', 'asc'))
            : $billings->orderByDesc('created_at');

        return Billing::collection($billings->paginate($request->query('per_page', 50)));
    }
}