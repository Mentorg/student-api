<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use F9Web\ApiResponseHelpers;
use Illuminate\Contracts\Hashing\Hasher;

class UserRepository
{
    use ApiResponseHelpers;

    /**
     * @var User
     * @var Hasher
     */
    protected $user;
    protected $hash;

    /**
     * UserRepository constructor
     *
     * @param User $user
     * @param Hasher $hash
     */
    public function __construct(User $user, Hasher $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
    }

    /**
     * Get Query Builder
     *
     * @return Builder
     */
    public function query()
    {
        return $this->user->newQuery();
    }

    public function queryWithRoles()
    {
        return $this->user->with('roles')->newQuery();
    }

    /**
     * Pagination
     *
     * @param int $count
     * @return mixed
     */
    public function paginate(int $count = 10)
    {
        return $this->user->paginate($count);
    }

    /**
     * Get user by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->respondWithSuccess($this->user->where('id', $id)->get());
    }

    /**
     * Update user
     *
     * @param $data
     * @param $id
     */
    public function update($data, $id)
    {
        $user = $this->user->find($id);
        $user->fill($data);
//        $user->revokePermissionTo($data['delete_roles']); # To delete roles
        $user->syncRoles($data['roles']);
        $user->update();

        return $this->respondWithSuccess($user);
    }

    /**
     * Delete user
     *
     * @param $id
//     * @return String
     */
    public function delete($id)
    {
        $user = $this->user->find($id);
        $user->delete();

        return $this->respondWithSuccess($user);
    }
}
