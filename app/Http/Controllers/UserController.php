<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthManager;
use Illuminate\Validation\Factory;
use Illuminate\Http\Request;
use InvalidArgumentException;

class UserController extends Controller
{
    /**
     * @var userService
     * @var $validation
     * @var $auth
     */
    protected $userService;
    protected $validation;
    protected $auth;

    /**
     * UserController constructor
     *
     * @param UserService $userService
     * @param Factory $validation
     * @param AuthManager $auth
     *
     */
    public function __construct(UserService $userService, Factory $validation, AuthManager $auth)
    {
        $this->userService = $userService;
        $this->validation = $validation;
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      tags={"users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    /**
     * Display a listing of the resources
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = $this->auth->id();
        $this->authorize('viewAny', $user);
        return $this->userService->getWithPagination();
    }

    /**
     * Store a newly create resource in storage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUserById",
     *      tags={"users"},
     *      summary="Get user information",
     *      description="Returns user data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    /**
     * Display the specified resource
     *
     * @param User $user
     * @return String
     */
    public function show($id)
    {
        $user = User::find($id);
        $this->authorize('view', $user);
        try {
            return $this->userService->getById($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"users"},
     *      summary="Update existing user",
     *      description="Returns updated user data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    /**
     * Update the specified resource
     *
     * @param Request $request
     * @param User $user
     * @return String
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->authorize('update', $user);
        $validator = $this->validation->make($request->all(), [
            'name' => ["bail", "min:3"],
            'email' => "email",
            'password' => "min:3",
            'roles' => "bail"
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }
        try {
            return $this->userService->updateUser($request->all(), $id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"users"},
     *      summary="Delete existing user",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    /**
     * Delete the specified resource from storage
     *
     * @param User $user
     * @return String
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $this->authorize('delete', $user);
        try {
            return $this->userService->deleteById($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
