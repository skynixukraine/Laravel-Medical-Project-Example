<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Doctors"},
 *     path="/api/v1/logout",
 *     summary="Revoke current token",
 *     description="Revoke current token",
 *     @OA\Response(response=200, description="Token has been revoked"),
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
class Logout extends ApiController
{
    public function __invoke(Request $request): void
    {
        $request->user()->token()->revoke();
    }
}