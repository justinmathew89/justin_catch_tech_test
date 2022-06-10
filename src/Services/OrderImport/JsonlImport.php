<?php

namespace App\Services\OrderImport;

use App\Services\OrderImport\Exceptions\OrderFileMissingException;
use Rs\JsonLines\JsonLines;

class JsonlImport implements IFileImport
{
    use Trait\FileImportTrait;
    public $orderFileUrl = null;

    /**
     * @inheritDoc
     */
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

}