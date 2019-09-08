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

    public function getCombinedItems($items, $fnCbk){

        return array_reduce($items, function($totalItems, $item) use($fnCbk){
                            if(! $totalItems) $totalItems = [];

                            $rtItems = $fnCbk($item); 
                            $totalItems = is_array($rtItems)? array_merge($totalItems, $rtItems): $totalItems = $totalItems;
                            return $totalItems;
                        });

    }

    public function itemsHelper($items, $fnCbk, $includeKey = false){
        $itemsObj = $includeKey? array_keys($items): $items;
        return array_reduce($itemsObj, function($totalItems, $item) use($fnCbk, $items, $includeKey){

                            if(! $totalItems) $totalItems = [];
                            if($includeKey && ! isset($totalItems[$item])) $totalItems[$item] = [];
                            $itemIsTr  = is_string($item);

                            $rtItems = ($itemIsTr  && $includeKey && count($items[$item]))? $fnCbk($items[$item], $item):false;
                            (! $rtItems && is_object($item))? $rtItems = $fnCbk($item): false; 

                            $totalItems = is_array($rtItems)? array_merge($totalItems, $rtItems): $totalItems = $totalItems;
                            return $totalItems;
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