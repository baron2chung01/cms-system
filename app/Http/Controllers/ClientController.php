<?php

namespace App\Http\Controllers;

use App\DataTables\ClientDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Assets;
use App\Models\Client;
use App\Models\Member;
use App\Repositories\ClientRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ClientController extends AppBaseController
{
    /** @var ClientRepository $clientRepository*/
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     */
    public function index(ClientDataTable $clientDataTable)
    {
        $this->authorize('members_access');

        return $clientDataTable->render('clients.index');
    }

    /**
     * Show the form for creating a new Client.
     */
    public function create()
    {
        $this->authorize('members_create');

        $status = Client::STATUS;
        return view('clients.create', compact('status'));
    }

    /**
     * Store a newly created Client in storage.
     */
    public function store(CreateClientRequest $request)
    {
        $this->authorize('members_create');

        $input = $request->all();

        // input encrypted password
        $input['password'] = bcrypt($input['password']);

        $client = $this->clientRepository->create($input);

        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(80, 80);
                Storage::disk('public')->put($imageName, $imageResized->encode());

                Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $client->clients_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => 'image',
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        Member::create([
            'members_uuid' => Str::uuid(),
            'clients_uuid' => $client->clients_uuid,
            'name'         => $input['last_name'] . $input['first_name'],
            'email'        => $input['email'],
            'phone'        => $input['phone'],
            'password'     => $input['password'],
            'status'       => Member::ACTIVE,
            'type'         => Member::CLIENT,
        ]);

        Flash::success('Client saved successfully.');

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified Client.
     */
    public function show($id)
    {
        $this->authorize('members_show');

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $status = Client::STATUS;

        $images = $client->image()->get();

        $pointRecords = $client->member->pointHistories;

        return view('clients.show', compact('client', 'status', 'images', 'pointRecords'));
    }

    /**
     * Show the form for editing the specified Client.
     */
    public function edit($id)
    {
        $this->authorize('members_edit');

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $status = Client::STATUS;

        $images = Assets::where('module_uuid', $client->clients_uuid)->get();

        return view('clients.edit', compact('client', 'status', 'images'));
    }

    /**
     * Update the specified Client in storage.
     */
    public function update($id, UpdateClientRequest $request)
    {
        $this->authorize('members_edit');

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $input = $request->all();

        if (!isset($input['password'])) {
            $input['password'] = $client->password;
        }

        // input encrypted password
        $input['password'] = bcrypt($input['password']);

        $client = $this->clientRepository->update($input, $id);

        $client->member->update([
            'name'     => $input['last_name'] . $input['first_name'],
            'email'    => $input['email'],
            'phone'    => $input['phone'],
            'password' => $input['password'] ?? $client->member->password,
            'status'   => Member::ACTIVE,
            'type'     => Member::CLIENT,
        ]);

        Flash::success('Client updated successfully.');

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('members_delete');

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $client->update([
            'status' => Client::DELETE,
        ]);

        $this->clientRepository->delete($id);

        Flash::success('Client deleted successfully.');

        return redirect(route('clients.index'));
    }
}
