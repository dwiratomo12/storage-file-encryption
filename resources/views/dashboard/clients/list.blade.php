@extends('layouts.dashboard')


@section('content')
  <div class="mb-2">
    <a href="{{ route('dashboard.clients.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> user</a>
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
          <form action="{{ url('dashboard/clients')}}" method="GET">
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
      @if($clients->total())
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
            @foreach ($clients as $client)
                <tr>
                  <td scope="row">{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</td scope="row">
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->email }}</td>
                  <td>
                      <a href="{{ route('dashboard.clients.edit', $client->id) }}" title="edit" class="btn btn-success btn-sm">
                        <i class="fas fa-pen"></i></a>
                      <button class="btn btn-sm btn-danger" title="Delete" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                      </button>
                  </td>
                </tr>
            @endforeach
        </tbody>
        </table> 

        {{ $clients->appends($request)->links() }}
      @else
          <h4 class="text-center p-3">{{ __('messages.no_data', ['module' => 'User']) }}</h4>
      @endif
    </div>
  </div>

  {{-- delete modal --}}
  @if(isset($client))
  <div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Delete</h5>
          <button type="button" class="close" title="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <p>Anda yaking ingin hapus user ?</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
          <form action="{{ route('dashboard.clients.delete', $client->id) }}" method="POST">
            @csrf
            @method('delete')
            <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"> Delete</i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
   
@endsection