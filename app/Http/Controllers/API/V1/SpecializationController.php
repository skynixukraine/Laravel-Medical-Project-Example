<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\SpecializationResource;
use App\Models\Specialization;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SpecializationController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"Specializations"},
     *     path="/api/v1/specializations",
     *     summary="Get all specializations",
     *     description="Get all specializations",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          property="data",
     *                          @OA\Items(
     *                              type="object",
     *                              ref="#/components/schemas/SpecializationResource"
     *                          ),
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
    public function index(Request $request)
    {
        return SpecializationResource::collection(Specialization::all());
    }
}