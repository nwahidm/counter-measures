<?php

namespace App\Helpers;

use DB;
use File;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{

    public static function uploadImage(UploadedFile $file, $path, $prefix, $width = 1000, $height = 700, $quality = 70)
    {
        $basepathStorage = storage_path('app/public');
        $directoryPath = $basepathStorage . '/' . $path;

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $resultUpload = [];
        try {
            $filename = $prefix . '-' . Carbon::now()->timestamp . uniqid() . '.' . $file->getClientOriginalExtension();
            $image = Image::make($file);
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($directoryPath . '/' . $filename, $quality);

            $resultUpload = [
                'file_type' => $file->getMimeType(),
                'file_size' => $image->filesize(),
                'file_name' => $filename
            ];

            return $resultUpload;
        } catch (\Exception $ex) {
            return $resultUpload;
        }
    }

    public static function uploadImageKtp(UploadedFile $file, $path, $prefix, $width = 1000, $height = 700, $quality = 70)
    {
        $basepathStorage = storage_path('app/public');
        $directoryPath = $basepathStorage . '/' . $path;

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $resultUpload = [];
        try {
            $filename = $prefix . '.jpg';
            $image = Image::make($file);
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($directoryPath . '/' . $filename, $quality);

            $resultUpload = [
                'file_type' => $file->getMimeType(),
                'file_size' => $image->filesize(),
                'file_name' => $filename
            ];

            return $resultUpload;
        } catch (\Exception $ex) {
            return $resultUpload;
        }
    }


    public static function uploadFile(UploadedFile $file, $path, $prefix)
    {
        $basepathStorage = storage_path('app/public');
        $directoryPath = $basepathStorage . '/' . $path;

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $resultUpload = [];
        try {
            $filename = $prefix . '-' . Carbon::now()->timestamp . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($path . $filename, File::get($file));
            $resultUpload = [
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_name' => $filename
            ];

            return $resultUpload;
        } catch (\Exception $ex) {
            return $resultUpload;
        }
    }

    public static function deleteFile($path)
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public static function createFile($content, $mime, $filename)
    {
        $response = Response::make($content, 200);
        $response->withHeaders("Content-Type", $mime);
        $response->withHeaders("Content-Disposition", 'attachment; filename=' . $filename);

        return $response;
    }

    public static function uploadToFileService(UploadedFile $file, String $bucket, String $prefixFileName)
    {
        $curl = curl_init();
        $url = config('constant.portal_asset_url').'/api/v1/file/create';
        $headers = array("Content-Type:multipart/form-data");
        $postfields = [
            'file' => curl_file_create($file->getRealPath()),
            'bucket' => $bucket,
            'filename' => $prefixFileName . '-' . time() . "." . explode('/', $file->getMimeType())[1],
        ];
        $filesize = $file->getSize();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => $filesize,
            CURLOPT_RETURNTRANSFER => true
        ); // cURL options
        curl_setopt_array($curl, $options);
        curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            curl_close($curl);
            return redirect()->back()->with('error', 'upload image failed');
        }
        curl_close($curl);
        return $postfields['filename'];
    }

    public static function loadSlideImage($filename)
    {
        if ($filename == null) return asset('image/default.jpg');
        $url = config('constant.portal_asset_url');
        return "{$url}/api/v1/file/get?bucket=portal&filename=$filename&download=false";
    }

    public static function loadDocs($filename)
    {
        if ($filename == null) return asset('image/default.jpg');
        $url = config('constant.portal_asset_url');
        return "{$url}/api/v1/file/get?bucket=portal&filename=$filename&download=false";
    }

    public static function loadApplicationLogo($filename)
    {
        if ($filename == null) return asset('image/default.jpg');
        $url = config('constant.portal_asset_url');
        return "{$url}/api/v1/file/get?bucket=portal&filename=$filename&download=false";
    }
}
