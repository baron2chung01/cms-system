<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Shop;
use App\Models\ShopHasUser;
use App\Models\User;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index(UserDataTable $userDataTable)
    {
        $this->authorize('users_access');

        return $userDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $this->authorize('users_create');

        $shopIds = collect();

        $shops = Shop::where('status', Shop::ACTIVE)->get();

        return view('users.create', compact('shopIds', 'shops'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('users_create');

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = $this->userRepository->create($input);

        if (isset($request->shops)) {
            foreach ($request->shops as $shopId) {
                ShopHasUser::create(
                    [
                        'shop_id' => $shopId,
                        'user_id' => $user->id,
                    ]
                );
            }
        }

        //assign role for permission use
        $user->assignRole(strtolower(User::ROLE[$input['role']]));

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $this->authorize('users_show');

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $this->authorize('users_edit');

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $shopIds = $user->shops->pluck('id');

        $shops = Shop::where('status', Shop::ACTIVE)->get();

        return view('users.edit', compact('user', 'shopIds', 'shops'));
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateUserRequest $request)
    {
        $this->authorize('users_edit');

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        $input = $request->all();

        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }
        $user = $this->userRepository->update($input, $id);

        if (isset($request->shops)) {
            $oriShop = $user->shops->pluck('id')->toArray();

            $deleteShop = array_diff($oriShop, $input['shops']);
            $createShop = array_diff($input['shops'], $oriShop);

            foreach ($deleteShop as $shopId) {
                ShopHasUser::where('shop_id', $shopId)->where('user_id', $id)->forceDelete();
            }
            foreach ($createShop as $shopId) {
                ShopHasUser::create(
                    [
                        'shop_id' => $shopId,
                        'user_id' => $id,
                    ]
                );
            }
        }

        //assign role for permission use
        $user->syncRoles([strtolower(User::ROLE[$input['role']])]);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('users_delete');

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
