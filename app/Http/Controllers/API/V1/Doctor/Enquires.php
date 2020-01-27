<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Enquire as EnquireResource;
use App\Models\Doctor;
use App\Models\Enquire;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Doctors"},
 *     path="/api/v1/doctors/{id}/enquires",
 *     summary="Get enquires page",
 *     description="Get enquires page",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="A doctor's id",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\Parameter(
 *          name="with_archived",
 *          required=false,
 *          description="Include archived enquires or not. Default not",
 *          in="query",
 *          example="1"
 *     ),
 *     @OA\Parameter(
 *          name="status",
 *          required=false,
 *          description="Filter enquires by status. Possible values: UNREAD, READ, ARCHIVED",
 *          in="query",
 *          example="UNREAD"
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
 *          name="search_field",
 *          required=false,
 *          description="Search field. Possible values: id, first_name, last_name, phone_number, email",
 *          in="query",
 *          example="first_name"
 *     ),
 *     @OA\Parameter(
 *          name="order_field",
 *          required=false,
 *          description="Order field. Possible values: id, first_name, last_name, phone_number, email, status, conclusion, created_at",
 *          in="query",
 *          example="first_name"
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
 *         description="Enquires has been succesfully received",
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
class Enquires extends ApiController
{
    public function __invoke(Request $request, Doctor $doctor)
    {
        $enquires = $doctor->enquires()
            ->where($request->only(['gender', 'first_name', 'last_name']));

        if ($request->has('status')) {
            $enquires->where('status', $request->query('status'));
        } else if (!(bool) $request->query('with_archived', false)) {
            $enquires->where('status', '!=', Enquire::STATUS_ARCHIVED);
        }

        if ($request->has('created_at')) {
            $enquires->whereDate('created_at', $request->query('date'));
        }

        $sortableFields = ['id', 'first_name', 'last_name', 'phone_number', 'email', 'status', 'conclusion', 'created_at'];
        $searchableFields = ['id', 'first_name', 'last_name', 'phone_number', 'email'];

        if ($request->has('search', 'search_field')
            && collect($searchableFields)->contains($request->query('search_field'))) {
            $enquires->where($request->query('search_field'), 'LIKE', '%' . $request->query('search') . '%');
        }

        $request->has('order_field')
        && collect($sortableFields)->contains($request->query('order_field'))
            ? $enquires->orderBy($request->query('order_field'), $request->query('order_direction', 'asc'))
            : $enquires->orderBy('status')->orderByDesc('created_at');

        return EnquireResource::collection($enquires->with('answers')->paginate($request->query('per_page', 50)));
    }
}