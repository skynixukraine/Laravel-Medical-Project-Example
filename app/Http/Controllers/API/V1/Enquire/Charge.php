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
use Illuminate\Support\Facades\Log;
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

        Log::info('Start pay');
        
        if ($request->type != PaymentMethod::CREDIT_CARD_METHOD) {

            Log::info('Payment by service: ' . $request->type);
            
            $response = Source::retrieve($request->code);

            Log::info('Payment stripe status: ' . $response->status);
            
            throw_if($response->status != 'chargeable', ValidationException::withMessages([
                'status' => __('Your status is ' . $response->status),
            ]));
        }

        Log::info('Start charge');
        
        throw_if($enquire->doctor->pricePolicy == null, ValidationException::withMessages([
            'price_policy_id' => __('Your price policy is undefined'),
        ]));

        $price = $enquire->doctor->pricePolicy->enquire_total_price;
        $fee = $enquire->doctor->pricePolicy->enquire_admins_fee;
        $currency = $enquire->doctor->pricePolicy->currency;

        $params = [
            'amount' => $price,
            'currency' => $currency,
            'source' => $request->code,
            'transfer_group' => 'enquire_payment',
            'description' => Setting::fetchValue('enquire_charge_description')
        ];
        
        if ($enquire->doctor->stripe_account_id !== null) {
            $params['destination'] = $enquire->doctor->stripe_account_id;
            $params['application_fee_amount'] = $fee;
        }

        Log::info('Payment params: ' . json_encode($params));
        
        $response = \Stripe\Charge::create($params);

        Log::info('Charge status: ' . print_r($response, true));
        
        $enquire->billing()->create([
            'amount' => $price,
            'admin_fee' => $fee,
            'currency' => $currency,
            'invoice_1A_factor' => $enquire->doctor->pricePolicy->invoice_1A_factor,
            'invoice_1A_price' => $enquire->doctor->pricePolicy->invoice_1A_price,
            'invoice_5A_factor' => $enquire->doctor->pricePolicy->invoice_5A_factor,
            'invoice_5A_price' => $enquire->doctor->pricePolicy->invoice_5A_price,
            'invoice_75A_factor' => $enquire->doctor->pricePolicy->invoice_75A_factor,
            'invoice_75A_price' => $enquire->doctor->pricePolicy->invoice_75A_price,
        ]);

        Log::info('Update billing');
        
        $enquire->update([
            'payment_status' => Enquire::PAYMENT_STATUS_PAID,
            'hash' => Hash::make(Str::random(100) . time()),
        ]);

        Log::info('Update status enquire');

        event(new EnquireCreated($enquire));

        Log::info('Create event');
        
        return EnquireResource::make($enquire);
    }
}