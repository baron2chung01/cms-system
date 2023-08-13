<?php

namespace App\Http\Controllers;

use App\DataTables\MemberDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Client;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends AppBaseController
{
    /** @var MemberRepository $memberRepository*/
    private $memberRepository;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->memberRepository = $memberRepo;
    }

    /**
     * Display a listing of the Member.
     */
    public function index(MemberDataTable $memberDataTable)
    {
        $this->authorize('members_access');

        return $memberDataTable->render('members.index');
    }

    /**
     * Show the form for creating a new Member.
     */
    public function create()
    {
        $this->authorize('members_create');

        $status = Member::STATUS;
        $type   = Member::TYPE;
        return view('members.create', compact('status', 'type'));
    }

    /**
     * Store a newly created Member in storage.
     */
    public function store(CreateMemberRequest $request)
    {
        $this->authorize('members_create');

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $uuid = Str::uuid();

        $client = Client::create([
            'clients_uuid' => $uuid,
            'first_name'   => $input['name'],
            'last_name'    => $input['name'],
            'username'     => $input['email'],
            'password'     => $input['password'],
            'email'        => $input['email'],
            'subscription' => 0,
        ]);

        $member = Member::create(array_merge($input, ['clients_uuid' => $uuid]));

        Flash::success('Member saved successfully.');

        return redirect(route('members.index'));
    }

    /**
     * Display the specified Member.
     */
    public function show($id)
    {
        $this->authorize('members_show');

        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            Flash::error('Member not found');

            return redirect(route('members.index'));
        }

        return view('members.show')->with('member', $member);
    }

    /**
     * Show the form for editing the specified Member.
     */
    public function edit($id)
    {
        $this->authorize('members_edit');

        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            Flash::error('Member not found');

            return redirect(route('members.index'));
        }

        $status = Member::STATUS;
        $type   = Member::TYPE;

        return view('members.edit', compact('member', 'status', 'type'));
    }

    /**
     * Update the specified Member in storage.
     */
    public function update($id, UpdateMemberRequest $request)
    {
        $this->authorize('members_edit');

        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            Flash::error('Member not found');

            return redirect(route('members.index'));
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        if (!isset($input['password'])) {
            unset($input['password']);
        }

        $member = $this->memberRepository->update($input, $id);

        $member->client->update([
            'first_name'   => $input['name'],
            'last_name'    => $input['name'],
            'username'     => $input['email'],
            'password'     => $input['password'],
            'email'        => $input['email'],
        ]);


        Flash::success('Member updated successfully.');

        return redirect(route('members.index'));
    }

    /**
     * Remove the specified Member from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('members_delete');

        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            Flash::error('Member not found');

            return redirect(route('members.index'));
        }
        $member->update([
            'status' => Member::DELETE,
        ]);
        $member->client->update([
            'status' => Client::DELETE,
        ]);

        $member->client->delete();


        $this->memberRepository->delete($id);

        Flash::success('Member deleted successfully.');

        return redirect(route('members.index'));
    }
}
