<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

function generateRandomString($length = 15) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_len = strlen($chars);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, $chars_len - 1)];
    }
    return $randomString;
}

function isValidImage($file_name)
{
    if (mime_content_type($file_name) != 'image/jpeg')
        return false;
    $size = getimagesize($file_name);
    if (!$size || !($size[0] >= 120 && $size[1] >= 120) || $size[2] !== IMAGETYPE_JPEG)
        return false;
    return true;
}

class FileUpload extends Controller
{
    public function createForm(){
        return view('file-upload');
    }

    public function view(){
        return view('file-check');
    }

    public function fileUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg|max:2048',
        ]);

        $file = new File();
        $fileName = generateRandomString().'.jpeg';
        $request->file->move(public_path('uploads'), $fileName);
        $file->name = $request->file->getClientOriginalName();
        $file->file_path = '/app/public/uploads/' . $fileName;
        $file->save();
        return back()
            ->with('success','You file has been successfully  uploaded. But young man is too unreasonable to let you know the file name :)');
    }

    public function fileCheck(Request $request){
        $fileName = $request->filename;
        if(isValidImage($fileName)){
            return back()
                ->with('success','Image check pass');
        }
        return "File is not an image";
    }
}
