<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class SaveFileService {
    private $image;
    private $storage;
    private $model = null;
    private $isDelete = 0;

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function isDelete($isDelete)
    {
        $this->isDelete = $isDelete;
        return $this;
    }

    public function handle(){

        $dir = Storage::directories();

        if (!in_array('public/'.$this->storage.'/' , $dir)) {
            Storage::makeDirectory('public/'.$this->storage.'/');
        }

        if(!empty($this->image)) {
            $file_ext = $this->image->getClientOriginalExtension();
            $file_name = $this->image->getClientOriginalName();

            if($file_ext == 'docx' || $file_ext == 'doc' || $file_ext == 'pdf' || $file_ext == 'rft' || $file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png' || $file_ext == 'svg' || $file_ext == 'webp'){
                $fileImg = md5(uniqid().$file_name) . '.' . $file_ext;
                if ($this->model) {
                    $exp = strpbrk($this->model, '/');
                    @Storage::delete('public/'.$exp);
                }

                // $new = Image::make($this->image); //create image
                // $new->save(storage_path('app/public/'.$this->storage.'/'. $fileImg), 10);
                // $new->destroy(); //del momori

                Storage::put('public/'.$this->storage.'/' . $fileImg, File::get($this->image));
                $image = 'storage/'.$this->storage.'/'.$fileImg;
            }else{
                $image = null;
            }
        }else{
            if ($this->model) {
                if($this->isDelete < 1){
                    $image = $this->model;
                }else{
                    $exp = strpbrk($this->model, '/');
                    @Storage::delete('public/'.$exp);
                    $image = null;
                }
            }else{
                $image = null;
            }
        }
        return $image;

    }

}
