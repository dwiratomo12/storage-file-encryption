<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    
    public function index(Request $request, File $files)
    {
        $q = $request->input('q');

        $active = 'files';

        $files = $files->when($q, function ($query) use ($q) {
            return $query->where('filename', 'like', '%' . $q . '%');
        })
            ->paginate(10);

        $request = $request->all();
        return view('dashboard/file/list', [
            'files' => $files,
            'request' => $request,
            'active' => $active
        ]);
    }

    public function create()
    {
        $active = 'files';

        return view('dashboard/file/form', [
            'active'    => $active,
            'button'    => 'Create',
            'url'       => 'dashboard.files.store'
        ]);
    }

    public function store(Request $request, File $file)
    {
        $validator = Validator::make($request->all(), [
            'key'           => 'required|unique:App\Models\File,key',
            'description'   => 'required',
            'file'          => 'required|mimes:pdf,doc,docx,txt,zip', 'max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.files.create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $userId = Auth::user()->id;
            $requestFile = $request->file('file');
            $fileName = $requestFile->getClientOriginalName();
            $key = $request->input('key');
            // new Encrypter($key);
            // Storage::disk('s3')->put($fileName, fopen($requestFile, 'r+'), 'public'); //upload to s3
            Storage::disk('s3')->put($fileName, Crypt::encrypt($request->file('file')->getContent())); //upload to s3

            //save the file
            $file->user_id = $userId;
            $file->filename = $fileName;
            $file->key = $key;
            $file->save();

            return redirect()
                ->route('dashboard.files')
                ->with('message', __('messages.store', ['title' => $fileName]));
        }
    }

    public function downloads(File $file){
        $headers = ['Content-Disposition' => 'attachment; filename="' .$file->filename. '"'];
        // return response()->streamDownload(function(File $file) {
        //     echo Crypt::decrypt(Storage::disk('s3')->get($file->filename));
        // }, $file->filename);
        // $url = `https://s3.ap-southeast-1.amazonaws.com/sharing-file-wsf/$file->filename` . urlencode($file->filename);
        $requestFile = Storage::disk('s3')->get($file->filename);
        Storage::put('files/'.$file->filename, $requestFile);
        $getfile = Storage::get('files/'.$file->filename);
        $decryptfile = Crypt::decrypt($getfile);
        // $filename = $requestFile->getClientOriginalName();
        // $key = $file->key;
        // $decryptfile = Crypt::decrypt($key, $requestFile);
        // return Response::make(Crypt::decrypt(Storage::disk('s3')->get($file->filename), 200, $headers));
        return Response::make($decryptfile, 200, $headers);
        // return response()->streamDownload(function() use($decryptfile) {
        //     echo $decryptfile;
        // },200, $headers);
    }
    
    public function destroy(Request $request, File $file)
    {
        $requestFile = $request->file('file');
        $input_file = $requestFile->getClientOriginalName();
        // $file_name = pathinfo($input_file, PATHINFO_FILENAME);
        $title = $input_file;
        Storage::disk('s3')->delete($title);
        $file->delete();
        return redirect()
        ->route('dashboard.files')
        ->with('message', __('messages.delete', ['title' => $title]));
    }
}