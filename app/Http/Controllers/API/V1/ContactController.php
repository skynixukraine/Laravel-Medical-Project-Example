<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Events\ContactCreated;
use App\Models\Contact;
use App\Http\Requests\Contact\Create as Request;
use OpenApi\Annotations as OA;

class ContactController extends ApiController
{

    /**
     * @OA\Post(
     *     tags={"Contacts"},
     *     path="/api/v1/contact",
     *     summary="Create Support request",
     *     description="Create Support request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      format="string",
     *                      title="Name",
     *                      description="Name",
     *                      property="name",
     *                      example="John"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="email",
     *                      description="email",
     *                      property="email",
     *                      example="john@example.com"
     *                  ),
     *                  @OA\Property(
     *                      format="string",
     *                      title="Body",
     *                      description="Body",
     *                      property="body",
     *                      example="Test description"
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(response=200, description="An account has been succesfully closed"),
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
     *                              property="validation_code",
     *                              @OA\Items(
     *                                  type="string",
     *                                  example="Email is invalid."
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
     *                          example="No query results for model [App\Models\Contact]."
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
    public function index(Request $request)
    {
        try {
            $contact = Contact::create(
                $request->only('email', 'name', 'body')
            );

            event(new ContactCreated($contact));

            return ['status' => 'success'];
            
        } catch (Exception $e) {
            return ['status' => $e->getMessage()];    
        }
    }
}