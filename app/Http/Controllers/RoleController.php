<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Services\RoleService;
use Illuminate\Auth\AuthManager;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @var roleService
     * @var AuthManager
     */
    protected $roleService;
    protected $auth;

    /**
     * RolesController Constructor
     *
     * @param RoleService $roleService
     * @param AuthManager $auth
     */
    function __construct(RoleService $roleService, AuthManager $auth)
    {
        $this->roleService = $roleService;
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *      path="/roles",
     *      operationId="getRolesList",
     *      tags={"roles"},
     *      summary="Get list of roles",
     *      description="Returns list of roles",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     * Display a listing of the resource.
     *
     * @return String
     */
    public function index()
    {
        $role = $this->auth->id();
        $this->authorize('viewAny', $role);
        return $this->roleService->getAllRoles();
    }

    /**
     * @OA\Post(
     *      path="/roles",
     *      operationId="storeRole",
     *      tags={"roles"},
     *      summary="Store new role",
     *      description="Returns role data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreRoleRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return String
     */
    public function store(Request $request)
    {
        $role = $request->user()->id;
        $this->authorize('create', $role);
        $data = $request->only([
            'name',
            'permission'
        ]);

        try {
            return $this->roleService->saveRoleData($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/roles/{id}",
     *      operationId="getRoleById",
     *      tags={"roles"},
     *      summary="Get role information",
     *      description="Returns role data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return String
     */
    public function show($id)
    {
        $role = Role::find($id);
        $this->authorize('view', $role);
        try {
            return $this->roleService->getById($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/roles/{id}",
     *      operationId="updateRole",
     *      tags={"roles"},
     *      summary="Update existing role",
     *      description="Returns updated role data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateRoleRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return String
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $this->authorize('update', $role);
        $data = $request->only([
            'name',
            'permission'
        ]);
        try {
            return $this->roleService->updateRole($data, $id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/roles/{id}",
     *      operationId="deleteRole",
     *      tags={"roles"},
     *      summary="Delete existing role",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return String
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $this->authorize('delete', $role);
        try {
            return $this->roleService->deleteById($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
