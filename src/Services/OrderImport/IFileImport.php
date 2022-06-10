<?php

namespace App\Services\OrderImport;

use App\Services\OrderImport\Exceptions\OrderFileMissingException;

interface IFileImport
{
    /**
     * method to get the orders from the orderfile
     * @return iterable
     */
    public function getOrdersFromFile() :iterable;

    /**
     * * Validates and sets the order file url
     * @return string
     * @throws OrderFileMissingException
     */
    public function getOrderFileUrl() :string;

    /**
     * get the validated orderfile url as string
     * @param string $orderFileUrl
     * @return IFileImport
     */
    public function setOrderFileUrl(string $orderFileUrl) :IFileImport;
}