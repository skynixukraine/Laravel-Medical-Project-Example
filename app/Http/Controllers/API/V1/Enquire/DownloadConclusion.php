<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/download-conclusion",
 *     summary="Download enquire's conclusion",
 *     description="Download enquire's conclusion",
 *     @OA\Response(
 *          response=200,
 *          description="Verification code has been verified",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          type="string",
 *                          property="conclusion",
 *                          example="Ri0xLjMKMSAwIG9iago8PCAvVHlwZSAvQ2F0YWxvZwovT3V0bGluZXMgMiAwIFIKL1BhZ2VzIDMgMC"
 *                      )
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
class DownloadConclusion extends ApiController
{
    public function __invoke()
    {
        $pdf = PDF::loadView('pdf.conclusion', ['enquire' => Auth::user()]);
        return ['conclusion' => base64_encode($pdf->output())];
    }
}