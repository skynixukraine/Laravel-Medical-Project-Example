<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\ValidateHash as ValidateHashRequest;
use App\Models\Enquire;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * @OA\Post(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/validate-hash",
 *     summary="Validate enquires hash",
 *     description="Validate enquires hash",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      format="string",
 *                      title="hash",
 *                      description="Hash",
 *                      property="hash",
 *                      example="sdsjkncnccl23cn2l23lnd23d"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Hash code has been verified",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="integer",
 *                          property="id",
 *                          example="1"
 *                      )
 *                  }
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=422,
 *         description="There are some validation errors",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  title="Validation error",
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="The given data was invalid."
 *                      ),
 *                      @OA\Property(
 *                          property="errors",
 *                          format="object",
 *                          @OA\Property(
 *                              property="hash",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="Hash code is invalid."
 *                              ),
 *                          ),
 *                      ),
 *                  }
 *              ),
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
 *                          example="No query results for model [App\Models\Enquire]."
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
class ValidateHash extends ApiController
{
    public function __invoke(ValidateHashRequest $request)
    {
        $enquire = Enquire::query()->where('hash', $request->hash)->first();

        throw_if(!$enquire, AuthorizationException::class);

        return [
            'id' => $enquire->id,
        ];
    }
}