@extends('layouts.dashboard')


@section('content')
  <div class="mb-2">
    <a href="{{ route('dashboard.client.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> user</a>
  </div>

  @if(session()->has('message'))
    <div class="alert alert-success">
      <strong>{{ session()->get('message') }}</strong>
      <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-8 align-self-center">
          <h3>User</h3>
        </div>
        {{-- search form --}}
        <div class="col-4">
          <form action="{{ url('dashboard/client')}}" method="GET">
            <div class="input-group">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $request['q'] ?? '' }}">
              <div class="input-group-append">
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
        
    <div class="card-body p-0">
      @if($client->total())
        <table class="table table-borderless table-striped table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
        <tbody>
            @foreach ($client as $cli)
                <tr>
                  <td scope="row">{{ ($client->currentPage() - 1) * $client->perPage() + $loop->iteration }}</td scope="row">
                  <td>{{ $cli->name }}</td>
                  <td>{{ $cli->email }}</td>
                  <td>
                      <a href="{{ route('dashboard.client.edit', $cli->id) }}" title="edit" class="btn btn-success btn-sm">
                        <i class="fas fa-pen"></i></a>
                  </td>
                </tr>
            @endforeach
        </tbody>
        </table> 

        {{ $client->appends($request)->links() }}
      @else
          <h4 class="text-center p-3">{{ __('messages.no_data', ['module' => 'User']) }}</h4>
      @endif
    </div>
  </div>
   
@endsection