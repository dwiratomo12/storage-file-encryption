@extends('layouts.dashboard')


@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-8 align-self-center">
          <h3>Users</h3>
        </div>
      </div>
    </div>
        
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 offset-md-2">
        <form action="{{ route($url, $clients->id ?? '') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($clients))
            @method('put')
          @endif
          
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control @error('name') {{ 'is-invalid' }} @enderror" name="name" value="{{ old('name') ?? $clients->name ?? ''}}">
            @error('name')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control @error('email') {{ 'is-invalid' }} @enderror" name="email" value="{{ old('email') ?? $clients->email ?? ''}}">
            @error('email')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control @error('password') {{ 'is-invalid' }} @enderror" name="password" value="{{ old('password') ?? $clients->password ?? ''}}">
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
   
@endsection