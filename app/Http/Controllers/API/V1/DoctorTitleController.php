<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\DoctorTitle as DoctorTitleResource;
use App\Models\DoctorTitle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class DoctorTitleController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"DoctorTitle"},
     *     path="/api/v1/doctor-titles",
     *     summary="Get all titles",
     *     description="Get all titles",
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
     *                              ref="#/components/schemas/DoctorTitleResource"
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
    public function index(Request $request): AnonymousResourceCollection
    {
        return DoctorTitleResource::collection(DoctorTitle::all());
    }
}