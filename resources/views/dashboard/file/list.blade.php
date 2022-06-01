@extends('layouts.dashboard')


@section('content')
  <div class="mb-2">
    <a href="{{ route('dashboard.files.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Upload File</a>
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
          <h3>Files</h3>
        </div>
        {{-- search form --}}
        <div class="col-4">
          <form action="{{ url('dashboard/files')}}" method="GET">
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
      @if($files->total())
        <table class="table table-borderless table-striped table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>File</th>
              <th>Key</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
        <tbody>
            @foreach ($files as $file)
                <tr>
                  <th scope="row">{{ ($files->currentPage() - 1) * $files->perPage() + $loop->iteration }}</th scope="row">
                    <td class="col-thumbnail">
                      <p><strong>{{  $file->filename }}</strong></p>
                    </td>
                  <td>
                    <p><strong>{{ $file->key }}</strong></p>
                  </td>
                  <td>
                    {{-- <a href="{{ url('dashboard/movie/edit/'.$file->id) }}" title="edit" class="btn btn-success btn-sm">
                    <i class="fas fa-pen"></i></a> --}}

                      <a href="{{ route('dashboard.files.downloads', $file->id) }}" title="download" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i></a>
                  </td>
                </tr>
            @endforeach
        </tbody>
        </table> 

        {{ $files->appends($request)->links() }}
      @else
          <h4 class="text-center p-3">{{ __('messages.no_data', ['module' => 'File']) }}</h4>
      @endif
    </div>
  </div>
   
@endsection