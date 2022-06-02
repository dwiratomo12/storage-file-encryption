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

        $active = 'Files';

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
            $key = Crypt::encryptString($key);
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

    public function downloads(Request $request, File $file){
        $headers = ['Content-Disposition' => 'attachment; filename="' .$file->filename. '"'];
        // $url = `https://s3.ap-southeast-1.amazonaws.com/sharing-file-wsf/$file->filename` . urlencode($file->filename);
        if (Crypt::decryptString($file->key) == $request->input('key')){
            $requestFile = Storage::disk('s3')->get($file->filename); //ambil file dari aws s3
            Storage::put('files/'.$file->filename, $requestFile); // simpan file decrypt 
            $getfile = Storage::get('files/'.$file->filename);
            $decryptfile = Crypt::decrypt($getfile); //dekripsi file
            return Response::make($decryptfile, 200, $headers);
        }else{
            return redirect()
            ->route('dashboard.files')
            ->with('error', __('messages.wrong'));
        }
        
    }
    
    public function destroy(File $file)
    {
        $title = $file->filename;
        Storage::disk('s3')->delete($title);
        $file->delete();
        if (Storage::exists('files/'. $title)){
            Storage::delete('files/'. $title);
        }
        return redirect()
        ->route('dashboard.files')
        ->with('message', __('messages.delete', ['title' => $title]));
    }
}