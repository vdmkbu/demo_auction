<?php


namespace App\Http\View\Composers;


use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class ProfileComposer
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function compose(View $view)
    {
        $user = User::find(auth()->id());

        $view->with('user_account', $account = $this->userRepository->getUserAccount($user));
        $view->with('user_reserved_money', $reserved = $this->userRepository->getReservedMoney($user));
        $view->with('user_free_money', $this->userRepository->getFreeMoney($account, $reserved));
    }
}
