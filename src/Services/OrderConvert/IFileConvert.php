<?php

namespace App\Services\OrderConvert;

interface IFileConvert
{
    public function convertOrdersToFile($orders = []) :string;

    public function validateConvertedFile() :bool;

}