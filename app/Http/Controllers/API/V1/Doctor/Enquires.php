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
 *          description="Filter enquires by status. Possible values: NEW, AWAITING_PATIENT_RESPONSE, AWAITING_DOCTOR_RESPONSE, RESOLVED, ARCHIVED",
 *          in="query",
 *          example="NEW"
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
 *          description="Order field. Possible values: id, first_name, last_name, status, last_contacted_at, created_at",
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
 *     @OA\Parameter(
 *          name="created_at_from",
 *          required=false,
 *          description="Enquires must be newer then this value",
 *          in="query",
 *          example="2020-01-15"
 *     ),
 *     @OA\Parameter(
 *          name="created_at_to",
 *          required=false,
 *          description="Enquires must be older then this value",
 *          in="query",
 *          example="2020-01-20"
 *     ),
 *     @OA\Parameter(
 *          name="last_contacted_at_from",
 *          required=false,
 *          description="Last contact must be later this value",
 *          in="query",
 *          example="2020-01-15"
 *     ),
 *     @OA\Parameter(
 *          name="last_contacted_at_to",
 *          required=false,
 *          description="Last contact must be before this value",
 *          in="query",
 *          example="2020-01-20"
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
                           ->where('payment_status', Enquire::PAYMENT_STATUS_PAID);

        if ($request->created_at_from) {
            $enquires->whereDate('enquires.created_at', '>=', $request->created_at_from);
        }

        if ($request->created_at_to) {
            $enquires->whereDate('enquires.created_at', '<=', $request->created_at_to);
        }

        if ($request->last_contact_from) {
            $enquires->whereDate('last_contacted_at', '<=', $request->last_contact_from);
        }

        if ($request->last_contact_to) {
            $enquires->whereDate('last_contacted_at', '>=', $request->last_contact_to);
        }

        if ($request->status) {
            $enquires->where('status', $request->status);
        }

        if (!$request->with_archived) {
            $enquires->where('status', '!=', Enquire::STATUS_ARCHIVED);
        }

        if ($request->search) {
            $enquires->where(function ($query) use ($request) {
                $query->where('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $sortableFields = ['id', 'first_name', 'last_name', 'created_at', 'last_contacted_at', 'status'];

        $request->has('order_field') && collect($sortableFields)->contains($request->order_field)
            ? $enquires->orderBy($request->order_field, $request->query('order_direction', 'asc'))
            : $enquires->orderBy('status')->orderByDesc('created_at');

        return EnquireResource::collection($enquires->with('answers')->paginate($request->query('per_page', 50)));
    }
}