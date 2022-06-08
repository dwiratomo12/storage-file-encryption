<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Clients;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request, Clients $client)
    {
        $q = $request->input('q');

        $active = 'Users';

        $client = $client->when($q, function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%')
                ->orwhere('email', 'like', '%' . $q . '%');
        })
            ->paginate(10);

        $request = $request->all();
        return view('dashboard/clients/list', [
            'clients' => $client,
            'request' => $request,
            'active' => $active
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'Users';


        return view('dashboard/clients/form', [
            'active'    => $active,
            'button'    => 'Create',
            'url'       => 'dashboard.clients.store'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Clients $client)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'email'       => 'required',
            'password'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.clients.create')
                ->withErrors($validator)
                ->withInput();
        } else {
            //insert ke table user 
            $user = new User;
            $user->role = 'user';
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->remember_token = Str::random(60);
            $user->save();

            //insert ke table client
            $client->id =  $user->id;
            $client->name = $request->input('name');
            $client->email = $request->input('email');
            $client->password = bcrypt($request->input('password'));
            $client->save();


            return redirect()
                ->route('dashboard.clients')
                ->with('message', __('messages.user_store', ['title' => $request->input('name')]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Clients $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Clients $client, $id)
    {
        $active = 'Users';
        $client = Clients::find($id);
        return view('dashboard/clients/form', [
            'clients' => $client,
            'active'    => $active,
            'url'       => 'dashboard.clients.update',
            'button'    => 'Update'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clients $client, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'email'       => 'required',
            'password'    => 'required',
        ]);
        $client = Clients::find($id);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.clients.update', $client->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            //insert ke table client
            $client->nim = $request->input('name');
            $client->email = $request->input('email');
            $client->password = bcrypt($request->input('password'));
            $client->save();
            return redirect()
                ->route('dashboard.clients')
                ->with('message', __('messages.update', ['title' => $request->input('name')]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Clients::find($id);
        $title = $client->name;

        $client->delete();

        return redirect()
            ->route('dashboard.clients')
            ->with('message', __('messages.user_delete', ['title' => $title]));
    }
}
