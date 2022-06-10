<?php

namespace App\Services\OrderConvert;

interface IFileConvert
{
    /**
     * Function that converts orders to file
     * @param $orders
     * @return string
     */
    public function convertOrdersToFile($orders = []) :string;

    /**
     * Function to validate the converted file
     * not implemented
     * @return bool
     */
    public function validateConvertedFile() :bool;

}