<?php

namespace App\Services\OrderImport;

interface IFileImport
{
    public function getOrdersFromFile() :iterable;
    public function getOrderFileUrl() :string;
    public function setOrderFileUrl(string $orderFileUrl) :IFileImport;
}