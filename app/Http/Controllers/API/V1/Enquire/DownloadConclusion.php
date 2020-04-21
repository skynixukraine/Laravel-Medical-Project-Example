<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\DownloadConclusion as DownloadConclusionRequest;
use App\Models\Enquire;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as BaseStorage;

/**
 * @OA\Post(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/download-conclusion",
 *     summary="Download enquire's conclusion",
 *     description="Download enquire's conclusion",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
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
 *         response=403,
 *         description="Current user has not permissions to do this action",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          format="string",
 *                          property="message",
 *                          example="This action is unauthorized."
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
class DownloadConclusion extends ApiController
{
    public function __invoke(DownloadConclusionRequest $request, Enquire $enquire)
    {
        throw_if(!Hash::check($request->access_token, $enquire->token->access_token), AuthorizationException::class);

        $logo = base64_encode(file_get_contents(public_path() . '/images/dark_logo.png'));

        $doctorPhotoPath = public_path() . '/images/' . 'doctor-default-photo.png';
        
        if ($enquire->doctor->photo) {
            $doctorPhotoPath = BaseStorage::temporaryUrl($enquire->doctor->photo, now()->addMinutes(5));
        }

        $doctorPhoto = base64_encode(file_get_contents($doctorPhotoPath));

        $pdf = PDF::loadView('pdf.conclusion',
            [
                'enquire' => $enquire, 
                'logo' => $logo,
                'doctorPhoto' => $doctorPhoto,
            ]
        );
        return ['conclusion' => base64_encode($pdf->output()), 'name' => 'conclusion_' . $enquire->first_name .  '_' . $enquire->last_name . '.pdf'];
    }
}