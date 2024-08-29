<?php

namespace App\Service;

use App\Enums\UserRoleEnum;
use App\RepositoryInterface\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $userRepositoryInterface)
    {
    }


    public function listAllUsers(): Collection
    {
        return $this->userRepositoryInterface->all();
    }


    /**
     * Save a new Admin User
     * @param mixed $data
     * @return \Exception|\Illuminate\Database\Eloquent\Model
     */
    public function saveNewAdminUser($data): void
    {
        $data['role'] = UserRoleEnum::ADMIN;
        $this->createUser($data);
    }

    /**
     * Save a new Subscriber User
     * @param mixed $data
     * @return \Exception|\Illuminate\Database\Eloquent\Model
     */
    public function saveNewSubscriberUser($data): void
    {
        $data['role'] = UserRoleEnum::SUBSCRIBER;
        $this->createUser($data);
    }

    /**
     * Create a new User
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model| Exception
     */
    public function createUser(array $data): Model|Exception
    {
        DB::beginTransaction();
        try {
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepositoryInterface->create($data);
            DB::commit();
            return $user;
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Find a User by Id
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Exception
     */
    public function findUserById(int $id): Model|Exception
    {
        return $this->userRepositoryInterface->find($id);
    }

    /**
     * Update an user Service
     * @param mixed $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model|\Exception
     */
    public function updateUser($data, $id): Model|Exception
    {
        DB::beginTransaction();
        try {
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->userRepositoryInterface->update($data, $id);
            DB::commit();
            return $user;
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function deleteUser(int $id): void
    {
        DB::beginTransaction();
        try {

            $this->userRepositoryInterface->delete($id);
            DB::commit();
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
