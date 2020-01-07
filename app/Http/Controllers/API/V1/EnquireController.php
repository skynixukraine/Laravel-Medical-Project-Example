<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Enquire\Create;
use App\Http\Resources\EnquireResource;
use App\Models\Enquire;
use App\Models\EnquireAnswer;
use App\Models\Message;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EnquireController extends ApiController
{
    private $storage;

    /**
     * EnquireController constructor.
     * @param $storage
     */
    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @OA\Get(
     *     tags={"Enquires"},
     *     path="/api/v1/enquires",
     *     summary="Get enquires page",
     *     description="Get enquires page",
     *     @OA\Parameter(
     *          name="with_archived",
     *          required=false,
     *          description="Include archived enquires or not. Default not",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Parameter(
     *          name="status",
     *          required=false,
     *          description="Filter enquires by status. Possible values: UNREAD, READ, ARCHIVED",
     *          in="query",
     *          example="UNREAD"
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          required=false,
     *          description="Page number",
     *          in="query",
     *          example="1"
     *     ),
     *     @OA\Parameter(
     *          name="per_page",
     *          required=false,
     *          description="Items amount on page",
     *          in="query",
     *          example="15"
     *     ),
     *     @OA\Parameter(
     *          name="search",
     *          required=false,
     *          description="Search string",
     *          in="query",
     *          example="John"
     *     ),
     *     @OA\Parameter(
     *          name="search_field",
     *          required=false,
     *          description="Search field. Possible values: id, first_name, last_name, phone_number, email",
     *          in="query",
     *          example="first_name"
     *     ),
     *     @OA\Parameter(
     *          name="order_field",
     *          required=false,
     *          description="Order field. Possible values: id, first_name, last_name, phone_number, email, status, conclusion, created_at",
     *          in="query",
     *          example="first_name"
     *     ),
     *     @OA\Parameter(
     *          name="order_direction",
     *          required=false,
     *          description="Order direction. Possible values: asc, desc",
     *          in="query",
     *          example="asc"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enquires has been succesfully received",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  properties={
     *                      @OA\Property(
     *                          @OA\Items(
     *                              type="object",
     *                              ref="#/components/schemas/EnquireResource"
     *                          ),
     *                          title="Enquires",
     *                          description="Enquires list",
     *                          property="data",
     *                      ),
     *                      @OA\Property(
     *                          @OA\Items(
     *                              properties={
     *                                  @OA\Property(
     *                                      property="first",
     *                                      example="http://online-hautarzt.com/api/v1/enquires?page=1"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="last",
     *                                      example="http://online-hautarzt.com/api/v1/enquires?page=10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="prev",
     *                                      example="http://online-hautarzt.com/api/v1/enquires?page=4"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="next",
     *                                      example="http://online-hautarzt.com/api/v1/enquires?page=6"
     *                                  )
     *                              },
     *                          ),
     *                          property="links"
     *                      ),
     *                      @OA\Property(
     *                          @OA\Items(
     *                              properties={
     *                                  @OA\Property(
     *                                      property="current_page",
     *                                      example="5"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="from",
     *                                      example="9"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="last_page",
     *                                      example="10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="path",
     *                                      example="http://online-hautarzt.com/api/v1/enquires"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="per_page",
     *                                      example="2"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="to",
     *                                      example="10"
     *                                  ),
     *                                  @OA\Property(
     *                                      property="total",
     *                                      example="19"
     *                                  ),
     *                              },
     *                          ),
     *                          property="meta"
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
        $enquires = Auth::user()->enquires()->where(['is_paid' => true]);

        $enquires->where($request->only(['gender', 'first_name', 'last_name']));

        if ($request->has('status')) {
            $enquires->where('status', $request->query('status'));
        } else if (!(bool) $request->query('with_archived', false)) {
            $enquires->where('status', '!=', Enquire::STATUS_ARCHIVED);
        }

        if ($request->has('created_at')) {
            $enquires->whereDate('created_at', $request->query('date'));
        }

        $sortableFields = ['id', 'first_name', 'last_name', 'phone_number', 'email', 'status', 'conclusion', 'created_at'];
        $searchableFields = ['id', 'first_name', 'last_name', 'phone_number', 'email'];

        if ($request->has('search', 'search_field')
            && collect($searchableFields)->contains($request->query('search_field'))) {
            $enquires->where($request->query('search_field'), 'LIKE', '%' . $request->query('search') . '%');
        }

        $request->has('order_field')
        && collect($sortableFields)->contains($request->query('order_field'))
            ? $enquires->orderBy($request->query('order_field'), $request->query('order_direction', 'asc'))
            : $enquires->orderBy('status')->orderByDesc('created_at');

        return EnquireResource::collection($enquires->paginate($request->query('per_page', 50)));
    }
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
     *                  required={"first_name", "last_name", "gender", "date_of_birth", "phone_number", "email", "doctor_id"},
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
     *                      description="An enquire customer's gender",
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
    public function create(Create $request)
    {
        $enquire = new Enquire($request->only([
            'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'email', 'doctor_id'
        ]));

        DB::transaction(function () use ($request, $enquire) {
            $enquire->save();
            $enquire->location()->create($request->only([
                'address', 'state', 'city', 'country', 'postal_code', 'latitude', 'longitude'
            ]));

            foreach ($request->answers as $messageId => $answers) {
                $message = Message::query()->findOrFail($messageId);
                $processMethod = 'create' . Str::ucfirst(Str::camel($message->type)) . 'Answer';
                if (method_exists($this, $processMethod)) {
                    $this->$processMethod(new EnquireAnswer([
                        'message_id' => $message->id,
                        'enquire_id' => $enquire->id,
                    ]), $answers);
                }
            }
        });

        $enquire = $enquire->fresh();
        $enquire->wasRecentlyCreated = true;

        return EnquireResource::make($enquire);
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

        Validator::make(['image' => $image], [
            'image' => 'mimes:jpg,png,jpeg|max:50000'
        ])->validate();

        $enquireAnswer->value = $this->storage->saveEnquireImage($image);
        $enquireAnswer->saveOrFail();
    }

    /**
     * @OA\Get(
     *     tags={"Enquires"},
     *     path="/api/v1/enquires/{id}",
     *     summary="Get an enquires resource by id",
     *     description="Get a enquires resource by id",
     *     @OA\Response(
     *         response=200,
     *         description="An enquire has been succesfully received",
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
    public function show(Enquire $enquire)
    {
        return EnquireResource::make($enquire);
    }
}