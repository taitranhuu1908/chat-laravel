<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function getModel(): string
    {
        return User::class;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', 'like', $email)->first();
    }

    public function deleteById($id)
    {
        return $this->update($id, [
            'is_active' => false,
        ]);
    }
}
