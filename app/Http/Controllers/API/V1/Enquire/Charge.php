<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Events\EnquireCharge;
use App\Events\EnquireCreated;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\Enquire as EnquireResource;
use App\Models\Enquire;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Http\Requests\Enquire\Charge as Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stripe\Source;

/**
 * @OA\Patch(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires/{id}/charge",
 *     summary="Pay an enquire",
 *     description="Pay an enquire",
 *     @OA\Parameter(
 *          name="id",
 *          required=true,
 *          description="An enquire's identificator",
 *          in="path",
 *          example="1"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"code"},
 *                  @OA\Property(
 *                      format="string",
 *                      title="Code",
 *                      description="Code from stripe",
 *                      property="code",
 *                      example="ac_GW5JDW26mx3GRGimieN78KWUzG8wwcfg"
 *                  ),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="An enquire has been succesfully charge",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  properties={
 *                      @OA\Property(
 *                          ref="#/components/schemas/EnquireResource",
 *                          property="data"
 *                      )
 *                  }
 *              )
 *          )
 *     ),
 *     @OA\Response(response=304, description="An enquire already paid"),
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
 *         description="Enquiere not found",
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
class Charge extends ApiController
{
    public function __invoke(Request $request, Enquire $enquire)
    {
        abort_if($enquire->status === Enquire::PAYMENT_STATUS_PAID, 304);

        if ($request->type != PaymentMethod::CREDIT_CARD_METHOD) {

            $response = Source::retrieve($request->code);

            throw_if($response->status != 'chargeable', ValidationException::withMessages([
                'status' => __('Your status is ' . $response->status),
            ]));
        }

        $price = Setting::fetchValue('enquire_total_price', 0) * 100;
        $fee = Setting::fetchValue('enquire_admins_fee', 0) * 100;
        $currency = Setting::fetchValue('enquire_price_currency', 'usd');

        \Stripe\Charge::create([
            'amount' => $price,
            'currency' => $currency,
            'application_fee_amount' => $fee,
            'source' => $request->code,
            'destination' => $enquire->doctor->stripe_account_id,
            'transfer_group' => 'enquire_payment',
            'description' => Setting::fetchValue('enquire_charge_description')
        ]);

        $enquire->billing()->create([
            'amount' => $price,
            'currency' => $currency,
        ]);

        $enquire->update([
            'payment_status' => Enquire::PAYMENT_STATUS_PAID,
            'hash' => Hash::make(Str::random(100) . time()),
        ]);

        event(new EnquireCreated($enquire));

        return EnquireResource::make($enquire);
    }
}