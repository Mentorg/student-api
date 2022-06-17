<?php

namespace App\Repositories;

use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use F9Web\ApiResponseHelpers;

class RoleRepository
{
    use ApiResponseHelpers;

    /**
     * @var Role
     */
    protected $role;
    protected $permission;

    /**
     * RoleRepository constructor.
     *
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * Get all roles.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->respondWithSuccess($this->role->all());
    }

    /**
     * Get role by id.
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $roles = $this->role->where('id', $id)->first();
        $permissions = $this->permission->join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return $this->respondWithSuccess(['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Save Role
     *
     * @param $role
     * @return JsonResponse
     */
    public function save($data)
    {
//        return $this->getModel()->create($data);
        $role = new $this->role;
        $role->name = $data['name'];
        $role->syncPermissions($data['permission']);
        $role->save();

        return $this->respondCreated($role->fresh());
    }

    /**
     * Update Role
     *
     * @param $data
     * @return JsonResponse
     */
    public function update($data, $id)
    {
        $role = $this->role->find($id);
        $role->name = $data['name'];
        $role->syncPermissions($data['permission']);
        $role->update();

        return $this->respondWithSuccess($role);
    }

    /**
     * Delete Role
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $role = $this->role->find($id);
        $role->delete();

        return $this->respondWithSuccess($role);
    }
}
