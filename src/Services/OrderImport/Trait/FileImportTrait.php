<?php
namespace App\Services\OrderImport\Trait;

use App\Services\OrderImport\Exceptions\OrderFileMissingException;
use App\Services\OrderImport\IFileImport;


/**
 * Trait that has common methods used by the file processors
 */
trait FileImportTrait {

    /**
     * @inheritDoc
     */
    public function setOrderFileUrl(string $orderFileUrl): IFileImport
    {
        if (!filter_var($orderFileUrl, FILTER_VALIDATE_URL) === false
            || (!file_exists($orderFileUrl) || empty($orderFileUrl))
        ) {
            $this->orderFileUrl = $orderFileUrl;
            return $this;
        }
        throw new OrderFileMissingException('Order file not found at '. $orderFileUrl);

    }

    /**
     * @inheritDoc
     */
    public function getOrderFileUrl(): string
    {
        return $this->orderFileUrl;
    }
}