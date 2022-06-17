<?php

namespace App\Services;

use App\Filters\Search;
use App\Repositories\UserRepository;
use Exception;
use InvalidArgumentException;
use Illuminate\Database\DatabaseManager;
use Samushi\QueryFilter\Facade\QueryFilter;

class UserService
{
    /**
     * @var $userRepository
     * @var $databaseManager
     */
    protected $userRepository;
    protected $databaseManager;

    /**
     * UserService Constructor
     *
     * @param UserRepository $userRepository
     * @param DatabaseManager $databaseManager
     */
    public function __construct(UserRepository $userRepository, DatabaseManager $databaseManager)
    {
        $this->userRepository = $userRepository;
        $this->databaseManager = $databaseManager;
    }

    public function filters()
    {
        return [
            new Search(['name', 'roles.name'])
        ];
    }

    /**
     * Delete user by id
     *
     * @param $id
     * @return string
     */
    public function deleteById($id)
    {
        $this->databaseManager->beginTransaction();

        try {
            $user = $this->userRepository->delete($id);
            $this->databaseManager->commit();
            return $user;
        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Get & Search users with pagination
     *
     * @param int $count
     * @return mixed
     */
    public function getWithPagination(int $count = 10)
    {
        return QueryFilter::query($this->userRepository->queryWithRoles(), $this->filters())->paginate($count);
    }

    /**
     * Get user by id
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }

    /**
     * Update user data
     *
     * @param array $data
     * @return string
     */
    public function updateUser($data, $id)
    {
        $this->databaseManager->beginTransaction();
        try {
            $user = $this->userRepository->update($data, $id);
            $this->databaseManager->commit();
            return $user;
        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}
