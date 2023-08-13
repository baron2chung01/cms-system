<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberAPIRequest;
use App\Http\Resources\MemberResource;
use App\Models\Assets;
use App\Models\Client;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Class MemberAPIController
 */
class MemberAPIController extends AppBaseController
{
    private MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->memberRepository = $memberRepo;
    }

    /**
     * Display a listing of the Members.
     * GET|HEAD /members
     */
    public function index(Request $request): JsonResponse
    {
        $members = $this->memberRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($members->toArray(), 'Members retrieved successfully');
    }

    /**
     * Store a newly created Member in storage.
     * POST /members
     */
    public function store(CreateMemberAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        if ($input['password'] != $input['confirm_password']) {
            return $this->sendError('Confirm password does not match with password.');
        }

        $input['members_uuid'] = Str::uuid();
        $input['type'] = Member::CLIENT;
        $input['status'] = Member::ACTIVE;

        // handle soft delete account in register
        // find if email exists
        $member = Member::withTrashed()->where('email', $input['email'])->first();
        if ($member != null && $member->deleted_at == null) {
            return $this->sendError('This email is already registered.');
        } else if ($member != null && $member->deleted_at != null) {
            // account is registered and deleted before
            $member->update($input);
            $member->update([
                'status'     => Member::ACTIVE,
                'deleted_at' => null,
                'password'   => bcrypt($input['password']),
            ]);
            $member->client->update([
                'status'     => Client::ACTIVE,
                'deleted_at' => null,
                'password'   => bcrypt($input['password']),
            ]);
        } else {
            // normal flow
            $uuid = Str::uuid();

            $client = Client::create([
                'clients_uuid' => $uuid,
                'first_name'   => $input['name'],
                'last_name'    => $input['name'],
                'username'     => $input['email'],
                'password'     => bcrypt($input['password']),
                'email'        => $input['email'],
                'subscription' => 0,
            ]);

            $input['password'] = bcrypt($input['password']);
            $member = Member::create(array_merge($input, ['clients_uuid' => $uuid]));
        }
        return $this->sendResponse(MemberResource::make($member), 'Member saved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse(MemberResource::make($member), 'Member retrieved successfully');
    }

    /**
     * Update the specified Member in storage.
     * PUT/PATCH /members/{id}
     */
    public function update(Request $request): JsonResponse
    {
        $id = auth('api')->id();
        $member = Member::find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        if ($request->password) {

            if (!Hash::check($request->old_password, $member->password)) {
                return $this->sendError('新密碼與舊密碼不符');
            }

            if ($request->password != $request->confirm_password) {
                return $this->sendError('新密碼與確認密碼不符');
            }
        }

        $query = Member::where('email', $request->email)->get();

        if (count($query)) {
            // check if its same person
            if ($query[0]->id != $id) {
                return $this->sendError('此電郵已被註冊');
            }
        }

        if (isset($request->password)) {
            $member->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $member->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        // handle avatar upload

        $client = $member->client;

        if (!isset($client)) {
            return $this->sendError('Client not found, please update in backend.');
        }

        $image = $request->file('file');

        if (isset($image)) {
            $imageName = date("Ymdhis") . $image->getClientOriginalName();
            $imageResized = Image::make($image)->fit(80, 80);

            Storage::disk('public')->put($imageName, $imageResized->encode());

            $asset = Assets::updateOrCreate([
                'module_uuid' => $client->clients_uuid,
            ]
                , [
                    'assets_uuid'        => Str::uuid(),
                    'second_module_uuid' => null,
                    'resource_path'      => "storage/{$imageName}",
                    'file_name'          => pathinfo($image->getClientOriginalName())['filename'],
                    'type'               => 'image',
                    'module_type'        => null,
                    'created_by'         => $client->clients_uuid,
                    'file_size'          => $image->getSize(),
                    'status'             => Assets::ACTIVE,
                ]);
        }

        $member->refresh();

        return $this->sendResponse(MemberResource::make($member), '會員更新成功');
    }

    /**
     * Remove the specified Member from storage.
     * DELETE /members/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }
        $member->update([
            'status' => Member::INACTIVE,
        ]);
        $member->delete();
        return $this->sendSuccess('Member deleted successfully');
    }

    public function self()
    {
        $id = auth('api')->id();
        $member = Member::where('id', $id)->first();
        return $this->sendResponse(MemberResource::make($member), 'Get self info successfully');
    }

    public function selfResetPassword(Request $request)
    {
        $validated = $request->validate([
            'old_password'     => 'required|string|max:255',
            'password'         => 'required|string|min:8|max:255',
            'confirm_password' => 'required|string|min:8|max:255',
        ]);

        $id = auth('api')->id();

        $member = Member::find($id);
        if (Hash::check(bcrypt($request->old_password), $member->password)) {
            return $this->sendError('新密碼與舊密碼不符');
        };

        if ($request->password != $request->confirm_password) {
            return $this->sendError('密碼與確認密碼不符');
        }

        $member->update([
            'password' => bcrypt($request->password),
        ]);

        $member->refresh();

        return $this->sendResponse(MemberResource::make($member), '密碼重置成功');
    }

    public function avatarUpload(Request $request)
    {

        $id = auth('api')->id();
        $member = Member::find($id);
        $client = $member->client;

        if (!isset($client)) {
            return $this->sendError('找不到會員');
        }

        $image = $request->file('file');
        $imageName = date("Ymdhis") . $image->getClientOriginalName();
        $imageResized = Image::make($image)->fit(80, 80);

        Storage::disk('public')->put($imageName, $imageResized->encode());

        $asset = Assets::updateOrCreate([
            'module_uuid' => $client->clients_uuid,
        ]
            , [
                'assets_uuid'        => Str::uuid(),
                'second_module_uuid' => null,
                'resource_path'      => "storage/{$imageName}",
                'file_name'          => pathinfo($image->getClientOriginalName())['filename'],
                'type'               => 'image',
                'module_type'        => null,
                'created_by'         => $client->clients_uuid,
                'file_size'          => $image->getSize(),
                'status'             => Assets::ACTIVE,
            ]);

        return $this->sendSuccess('頭像上傳成功');

    }
}
