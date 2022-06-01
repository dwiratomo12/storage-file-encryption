@extends('layouts.dashboard')


@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-8 align-self-center">
          <h3>User</h3>
        </div>
        <div class="col-4 text-right">
          <button class="btn btn-sm text-secondary" title="Delete" data-toggle="modal" data-target="#deleteModal">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
        
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 offset-md-2">
        <form action="{{ route($url, $client->id ?? '') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($client))
            @method('put')
          @endif
          
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control @error('name') {{ 'is-invalid' }} @enderror" name="name" value="{{ old('name') ?? $client->name ?? ''}}">
            @error('name')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control @error('email') {{ 'is-invalid' }} @enderror" name="email" value="{{ old('email') ?? $client->email ?? ''}}">
            @error('email')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control @error('password') {{ 'is-invalid' }} @enderror" name="password" value="{{ old('password') ?? $client->password ?? ''}}">
            @error('password')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-group mb-0">
            <button type="button" onclick="window.history.back()" class="btn btn-sm btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-success btn-sm">{{ $button }}</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

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
          <p>Anda yaking ingin hapus {{$client->name}}</p>
        </div>

        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
          <form action="{{ route('dashboard.client.delete', $client->id) }}" method="POST">
            @csrf
            @method('delete')
            <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"> Delete</i></button>
          </form>
        </div> --}}
      </div>
    </div>
  </div>
  @endif
   
@endsection