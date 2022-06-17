<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Exception;
use InvalidArgumentException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\Factory;

class RoleService
{
    /**
     * @var RoleRepository
     * @var $databaseManager
     * @var $validation
     */
    protected $roleRepository;
    protected $databaseManager;
    protected $validation;

    /**
     * RoleService constructor
     *
     * @param RoleRepository $roleRepository
     * @param DatabaseManager $databaseManager
     * @param Factory $validation
     */
    public function __construct(RoleRepository $roleRepository, DatabaseManager $databaseManager, Factory $validation)
    {
        $this->roleRepository = $roleRepository;
        $this->databaseManager = $databaseManager;
        $this->validation = $validation;
    }

    /**
     * Delete role by id
     *
     * @param $id
     * @return string
     */
    public function deleteById($id)
    {
        $this->databaseManager->beginTransaction();

        try {
            $role = $this->roleRepository->delete($id);
            $this->databaseManager->commit();
            return $role;
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Get all roles
     *
     * @return String
     */
    public function getAllRoles()
    {
        return $this->roleRepository->getAll();
    }

    /**
     * Get role by id
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->roleRepository->getById($id);
    }

    /**
     * Update role data
     * Store to DB if there are no errors
     *
     * @param array $data
     * @return string
     */
    public function updateRole($data, $id)
    {
        $validator = $this->validation->make($data, [
            'name' => "min:3",
            'permission' => "required_without_all"
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $this->databaseManager->beginTransaction();

        try {
            $role = $this->roleRepository->update($data, $id);
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw new InvalidArgumentException($e->getMessage());
        }
        $this->databaseManager->commit();

        return $role;
    }

    /**
     * Validate role data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return string
     */
    public function saveRoleData(array $data)
    {
        $validator = $this->validation->make($data, [
            'name' => "required",
            'permission' => "required"
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->roleRepository->save($data);
    }
}
