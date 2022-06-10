<?php

namespace App\Services\OrderImport;

use App\Services\OrderImport\Exceptions\OrderFileProcessingException;

class XmlImport implements IFileImport
{
    use Trait\FileImportTrait;

    public $orderFileUrl = null;

    /**
     * @inheritDoc
     */
    public function getOrdersFromFile() :iterable
    {
        $orders = null;
        $xmlObject = simplexml_load_file($this->getOrderFileUrl());

        if ($xmlObject === FALSE) {
            $messages = [];
            foreach(libxml_get_errors() as $error) {
                $messages[] = $error->message;
            }
            throw new OrderFileProcessingException(implode(', ', $messages));
        }

        // converting to json so that it will be json object string
        $orders = json_encode($xmlObject);
        return json_decode($orders);
    }

}