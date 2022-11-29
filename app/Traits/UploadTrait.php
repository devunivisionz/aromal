<?php
namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use File;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $name . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move(public_path($folder), $file);
        //chmod((public_path($folder) . $file), 0777);

        return $folder . $file;
    }

    public function deleteOne($folder = null, $disk = 'public', $filename = null)
    {
        File::delete(public_path($folder) . $filename);
    }
}
