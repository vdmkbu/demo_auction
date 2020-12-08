<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'ASC')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_USER];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request, CreateNewUser $createNewUser)
    {
        $createNewUser->create($request->all());
        return redirect(route('admin.users.index'))->with('success', 'Пользователь успешно добавлен');
    }

    public function edit(User $user)
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_USER];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(User $user, Request $request, UpdateUserProfileInformation  $profileInformation)
    {
        $profileInformation->update($user, $request->all());
        return redirect(route('admin.users.index'))->with('success', 'Пользователь успешно обновлен');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect(route('admin.users.index'))->with('success', 'Пользователь удалён');
    }

    public function password(User $user)
    {
        return view('admin.users.password', compact('user'));
    }

    public function passwordUpdate(User $user, Request $request, ResetUserPassword $resetUserPassword)
    {
        $resetUserPassword->reset($user, $request->all());
        return redirect(route('admin.users.index'))->with('success', 'Пароль успешно обновлен');
    }
}
