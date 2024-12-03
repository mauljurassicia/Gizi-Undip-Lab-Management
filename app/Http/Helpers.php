<?php

use Illuminate\Http\File as HttpFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('saveImage')) {
    function saveImage($image, $storage, $isUpdate = false, $model = "")
    {
        $dir = Storage::directories();

        if (!in_array('public/' . $storage . '/', $dir)) {
            Storage::makeDirectory('public/' . $storage . '/');
        }

        if (!empty($image)) {
            $fileImg = uniqid() . '.' . $image->getClientOriginalExtension();
            if ($isUpdate) {
                @Storage::delete('public/' . $storage . '/' . $model);
            }
            Storage::put('public/' . $storage . '/' . $fileImg, File::get($image));
            $image = $fileImg;
        } else {
            if ($isUpdate) {
                $image = $model;
            } else {
                $image = NULL;
            }
        }
        return $image;
    }
}

if (!function_exists('saveImageOriginalName')) {
    function saveImageOriginalName($image, $storage, $isUpdate = false, $current_image = "")
    {
        $dir = Storage::directories();

        if (!in_array('public/' . $storage . '/', $dir)) {
            Storage::makeDirectory('public/' . $storage . '/');
        }

        if (!empty($image)) {
            $fileImg = getNameFile($image->getClientOriginalName());
            if ($isUpdate) {
                @Storage::delete('public/' . $storage . '/' . $current_image);
            }
            Storage::put('public/' . $storage . '/' . $fileImg, File::get($image));
            $image = $fileImg;
        } else {
            if ($isUpdate) {
                $image = $current_image;
            } else {
                $image = NULL;
            }
        }
        return $image;
    }
}

if (!function_exists('getNameFile')) {
    function getNameFile($slug, $val = 0)
    {

        if ($val > 0) {
            $data = explode('.', $slug);
            if ($val == 1) {
                $slug = $data[0] . '_' . $val;
            } else {
                $slug = explode("_", $data[0]);
                $length = count($slug);
                $last = $slug[$length - 1];
                $slug[$length - 1] = $last + 1;
                $slug = implode('_', $slug);
            }
            $slug = $slug . '.' . $data[1];
        }

        if (@Storage::exists($slug)) {
            $result = getNameFile($slug, $val + 1);
            return $result;
        }

        return $slug;
    }
}

if (!function_exists('getPercentage')) {
    function getPercentage($value, $total)
    {
        if ($total == 0) {
            return 0;
        }
        $percentage = ($value / $total) * 100;
        return $percentage;
    }
}

if (!function_exists('base64ToFile')) {
    function base64ToFile($base64, $filename = null)
    {
        // Remove data URI scheme if present
        $base64 = preg_replace('/^data:[\w\/\-\.]+;base64,/', '', $base64);
        
        // Decode the base64 string
        $fileData = base64_decode($base64);
        
        // Detect the MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $fileData);
        finfo_close($finfo);
        
        // Map MIME types to extensions
        $extensionMap = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            'image/webp' => '.webp',
            'image/bmp' => '.bmp',
            'image/svg+xml' => '.svg',
            'application/pdf' => '.pdf',
            'application/msword' => '.doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
        ];
        
        // Determine the extension
        $extension = $extensionMap[$mimeType] ?? '.bin'; // Default to .bin if unknown
        
        // Generate filename if not provided
        if (!$filename) {
            $filename = Str::uuid() . $extension;
        } elseif (!Str::endsWith($filename, $extension)) {
            $filename .= $extension;
        }
        
        // Create a temporary file with the correct extension
        $tempDir = sys_get_temp_dir();
        $tempFilePath = $tempDir . '/' . uniqid('base64_') . $extension;
        
        // Write the decoded file data to the temporary file
        file_put_contents($tempFilePath, $fileData);
        
        // Create an UploadedFile
        $file = new UploadedFile(
            $tempFilePath,
            $filename,
            $mimeType,
            null,
            true
        );
        
        return $file;
    }
    
}