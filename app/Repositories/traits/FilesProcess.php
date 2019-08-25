<?php

namespace App\Repositories\traits;

trait FilesProcess {

	public function getFilesParams($files){


        return collect($files)->reduce(function($total, $file){
            $reqName = $file->getClientOriginalName();
            $exPlode = explode(':', $reqName);

            $target = $exPlode[0];

            $path = $this->getFullPath($exPlode) .".".$file->extension();

            isset($total[$target])? $total[$target][$path] = $file: $total[$target] = [$path => $file];
            return $total;
        });

    }

    protected function getFullPath($paths){
        $path;
        foreach ($paths as $key => $value) {
            if($key == 0) continue;
            (isset($path))? $path = $path.'/'.$value: $path = $value;
        }
        return $path;
    }
}