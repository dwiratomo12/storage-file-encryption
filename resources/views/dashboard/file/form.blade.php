@extends('layouts.dashboard')


@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-8 align-self-center">
          <h3>Files</h3>
        </div>
      </div>
    </div>
        
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 offset-md-2">
        <form action="{{ route($url, $file->id ?? '') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($file))
            @method('put')
          @endif
          <div class="form-group mt-4">
            <div class="custom-file">
              <label for="filename" class="form-control-label">Upload File</label>
              <input type="file" class="form-control" value="old('filename')" id="filename" name="file">
              @error('filename')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group">
            <label for="key">Key</label>
            <input type="text" class="form-control @error('key') {{ 'is-invalid' }} @enderror" name="key" value="{{ old('key') ?? $file->key ?? ''}}">
            @error('key')
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