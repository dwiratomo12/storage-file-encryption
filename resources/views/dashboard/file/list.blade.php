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
  @elseif(session()->has('error'))
    <div class="alert alert-danger">
      <strong>{{ session()->get('error') }}</strong>
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
              <th colspan="2">File</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
        <tbody>
            @foreach ($files as $file)
              @if(auth()->user()->id == $file->user_id)
                <tr>
                  <th scope="row">{{ ($files->currentPage() - 1) * $files->perPage() + $loop->iteration }}</th scope="row">
                    <td class="col-thumbnail">
                      <p><strong>{{  $file->filename }}</strong></p>
                    </td>
                  <td>
                    {{-- <p><strong>{{ $file->key }}</strong></p> --}}
                  </td>
                  <td>
                    {{-- <a href="{{ url('dashboard/movie/edit/'.$file->id) }}" title="edit" class="btn btn-success btn-sm">
                    <i class="fas fa-pen"></i></a> --}}

                      {{-- <a href="{{ route('dashboard.files.downloads', $file->id) }}" title="download" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i></a> --}}
                      <button class="btn btn-sm btn-success" title="Delete" data-toggle="modal" data-target="#downloadModal">
                        <i class="fas fa-download"></i>
                      </button>
                      <button class="btn btn-sm btn-danger" title="Delete" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                      </button>
                  </td>
                </tr>
              @endif
            @endforeach
        </tbody>
        </table> 

        {{ $files->appends($request)->links() }}
      @else
          <h4 class="text-center p-3">{{ __('messages.no_data', ['module' => 'File']) }}</h4>
      @endif
    </div>
  </div>
   
  {{-- delete modal --}}
  @if(isset($file))
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
          <p>Anda yaking ingin hapus file</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
          <form action="{{ route('dashboard.files.delete', $file->id) }}" method="POST">
            @csrf
            @method('delete')
            <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"> Delete</i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
  
  {{-- download modal --}}
  @if(isset($file))
  <div class="modal fade" id="downloadModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Download</h5>
          <button type="button" class="close" title="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="{{ route('dashboard.files.downloads', $file->id) }}" method="GET">
            @csrf
            @if(isset($file))
              @method('put')
            @endif
            <div class="form-group">
              <label for="key">Key</label>
              <input type="text" class="form-control @error('key') {{ 'is-invalid' }} @enderror" name="key" value="{{ old('key') ?? ''}}">
              @error('key')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
              <button class="btn btn-sm btn-success" title="submit"><i class="fas fa-download"> Submit</i></button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  @endif

  
@endsection