<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Enquire;

use App\Events\EnquireCreated;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\Enquire\Create as CreateRequest;
use App\Http\Resources\Enquire as EnquireResource;
use App\Models\Enquire;
use App\Models\EnquireAnswer;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stripe\Charge;

/**
 * @OA\Post(
 *     tags={"Enquires"},
 *     path="/api/v1/enquires",
 *     summary="Create a new enquire",
 *     description="Create a new enquire",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  required={"first_name", "last_name", "gender", "date_of_birth", "phone_number", "email", "doctor_id", "answers"},
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's first name",
 *                      property="first_name",
 *                      example="John"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's last name",
 *                      property="last_name",
 *                      example="Carter"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's e-mail",
 *                      property="email",
 *                      example="test@gmail.com"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's gender. Possible values: MALE, FEMALE",
 *                      property="gender",
 *                      example="MALE"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's date of birth",
 *                      property="date_of_birth",
 *                      example="1993-02-05"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire customer's phone number",
 *                      property="phone_number",
 *                      example="+38 (099) 548-54-85"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      description="An enquire's doctor ID",
 *                      property="doctor_id",
 *                      example="5"
 *                  ),
 *                  @OA\Property(
 *                      format="array",
 *                      description="An enquire's answers",
 *                      property="answers"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="A location's country",
 *                      description="A location's country",
 *                      property="country",
 *                      example="USA"
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="A location's city",
 *                      description="A location's city",
 *                      property="city",
 *                      example="New York",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="A location's state",
 *                      description="A location's state",
 *                      property="state",
 *                      example="New York",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="A location's postal code",
 *                      description="A location's postal code",
 *                      property="postal_code",
 *                      example="12345",
 *                  ),
 *                  @OA\Property(
 *                      format="string",
 *                      title="A location's address",
 *                      description="A location's address",
 *                      property="address",
 *                      example="address",
 *                  ),
 *                  @OA\Property(
 *                      format="double",
 *                      title="A location's latitude",
 *                      description="A location's latitude",
 *                      property="latitude",
 *                      example=5.123,
 *                      nullable=true
 *                  ),
 *                  @OA\Property(
 *                      format="double",
 *                      title="A location's longitude",
 *                      description="A location's longitude",
 *                      property="longitude",
 *                      example=8.123,
 *                      nullable=true
 *                  )
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="An enquire has been succesfully created",
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
 *     @OA\Response(
 *         response=422,
 *         description="There are some validation errors",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  schema="ValidationError",
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
 *                              property="email",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The email field is required."
 *                              ),
 *                          ),
 *                          @OA\Property(
 *                              property="first_name",
 *                              @OA\Items(
 *                                  type="string",
 *                                  example="The first name field is required."
 *                              ),
 *                          ),
 *                      ),
 *                  }
 *              ),
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
 *                          example="Server Error."
 *                      ),
 *                  }
 *              )
 *          )
 *      )
 * )
 */
class Create extends ApiController
{
    public function __invoke(CreateRequest $request)
    {
        $enquire = new Enquire($request->only(
            'first_name', 'last_name', 'gender', 'date_of_birth',
            'phone_number', 'email', 'doctor_id'
        ));

        DB::transaction(function () use ($request, $enquire) {
            $enquire->authy_id = $this->getAuthyId($request->email, $request->phone_number, $request->country_code);
            $enquire->saveOrFail();

            $enquire->location()->create($request->only(
                'address', 'latitude', 'longitude',
                'city', 'state', 'postal_code', 'country'
            ));

            $this->processAnswers($enquire, $request->answers);
            $this->payForEnquire($enquire, $request->code);

        }, 2);

        $enquire = $enquire->fresh();
        $enquire->wasRecentlyCreated = true;

        event(new EnquireCreated($enquire));

        return EnquireResource::make($enquire);
    }

    private function getAuthyId(string $email, string $phoneNumber, string $countryCode)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        $id = app('authy')->registerUser($email, $phoneNumber, $countryCode)->id();

        throw_if(!$id, ValidationException::withMessages(['phone_number' => __('Phone number is invalid')]));

        return $id;
    }

    private function payForEnquire(Enquire $enquire, string $code): void
    {
        $price = Setting::fetchValue('enquire_total_price', 0) * 100;
        $fee = Setting::fetchValue('enquire_admins_fee', 0) * 100;
        $currency = Setting::fetchValue('enquire_price_currency', 'usd');

        Charge::create([
            'amount' => $price,
            'currency' => $currency,
            'application_fee_amount' => $fee,
            'source' => $code,
            'destination' => $enquire->doctor->stripe_account_id,
            'transfer_group' => 'enquire_payment',
            'description' => Setting::fetchValue('enquire_charge_description')
        ]);

        $enquire->billing()->create([
            'amount' => $price,
            'currency' => $currency,
        ]);
    }

    private function processAnswers(Enquire $enquire, $answers): void
    {
        foreach ($answers as $messageId => $answer) {
            $message = Message::query()->findOrFail($messageId);
            $processMethod = 'create' . Str::ucfirst(Str::camel($message->type)) . 'Answer';
            if (method_exists($this, $processMethod)) {
                $this->$processMethod(EnquireAnswer::create([
                    'message_id' => $message->id,
                    'enquire_id' => $enquire->id
                ]), $answer);
            }
        }
    }

    private function createTextAnswer(EnquireAnswer $enquireAnswer, $answers): void
    {
        $enquireAnswer->value = is_array($answers) ? ($answers[0] ?? null) : $answers;
        $enquireAnswer->saveOrFail();
    }

    private function createSelectAnswer(EnquireAnswer $enquireAnswer, $answers): void
    {
        $answers = is_array($answers) ? $answers : [$answers];

        foreach ($answers as $answer) {
            $currentEnquireAnswer = clone $enquireAnswer;
            $currentEnquireAnswer->message_option_id = (int) $answer;
            $currentEnquireAnswer->saveOrFail();
        }
    }

    private function createRadioAnswer(EnquireAnswer $enquireAnswer, $answers): void
    {
        $enquireAnswer->message_option_id = (int) is_array($answers) ? $answers[0] : $answers;
        $enquireAnswer->saveOrFail();
    }

    private function createBodySelectAnswer(EnquireAnswer $enquireAnswer, $answers): void
    {
        $answers = is_array($answers) ? $answers : [$answers];

        foreach ($answers as $answer) {
            $currentEnquireAnswer = clone $enquireAnswer;
            $currentEnquireAnswer->value = $answer;
            $currentEnquireAnswer->saveOrFail();
        }
    }

    private function createImageAnswer(EnquireAnswer $enquireAnswer, $answers): void
    {
        $image = is_array($answers) ? ($answers[0] ?? null) : $answers;

        Validator::make(['image' => $image], ['image' => 'mimes:jpg,png,jpeg|max:50000'])->validate();

//        FIXME: storage variable not exists here
        $enquireAnswer->value = $this->storage->saveEnquireImage($image);
        $enquireAnswer->saveOrFail();
    }
}