<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public const MODEL = User::class;

    public function allUsersWithRoles(){
        return User::with('roles');
    }

}
