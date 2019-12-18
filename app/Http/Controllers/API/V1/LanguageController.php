<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class LanguageController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"Languages"},
     *     path="/api/v1/languages",
     *     summary="Get all languages",
     *     description="Get all languages",
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
     *                              ref="#/components/schemas/LanguageResource"
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
        return LanguageResource::collection(Language::all());
    }
}