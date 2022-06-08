<?php

namespace App\Services\OrderImport;

use Rs\JsonLines\JsonLines;

class JsonlImport implements IFileImport
{
    public $orderFileUrl = '';

    public function getOrdersFromFile() :iterable
    {
        $orders = null;
         if (!empty($this->getOrderFileUrl())) {
             try{
                 $orders = (new JsonLines())->delineEachLineFromFile($this->getOrderFileUrl());
             } catch(\Exception $e) {
                 return null;
             }
         }
         return $orders;
    }

    public function getOrderFileUrl(): string
    {
        return $this->orderFileUrl;
    }

    public function setOrderFileUrl(string $orderFileUrl): IFileImport
    {
        $this->orderFileUrl = $orderFileUrl;
        return $this;
    }
}