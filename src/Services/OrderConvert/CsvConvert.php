<?php
namespace App\Services\OrderConvert;

class CsvConvert implements IFileConvert
{
    private $destinationFolder = 'public/';
    private $outputFileName = 'out.csv';

    private $headerRowColumns = [
        'order_id',
        'order_date_time',
        'total_order_value',
        'average_unit_price',
        'distinct_unit_count',
        'total_units_count',
        'customer_state'
    ];

    /**
     * @inheritDoc
     */
    public function convertOrdersToFile($orders = []) :string
    {
        if (!empty($orders)) {
            $outPutFile = $this->destinationFolder.$this->outputFileName;
            $fp = fopen($outPutFile, "w");
            fputcsv($fp, $this->headerRowColumns);
            foreach($orders as $orderDetail) {
                if (is_string($orderDetail)) {
                    $order = json_decode($orderDetail);
                }
                $uniqueItemCount = !empty($order->items) ? sizeof($order->items) : 0;
                $totalItemsCount = 0;
                $totalOrderValue = 0;
                foreach ($order->items as $items) {
                    $totalItemsCount += $items->quantity;
                    $totalOrderValue += ($items->quantity * $items->unit_price);
                }
                //calculating discount
                // sorting discounts based on priority
                $discounts = [];
                foreach ($order->discounts as $discount) {
                    $discounts[$discount->priority] = [
                        'type' => $discount->type,
                        'value' => $discount->value
                    ];
                }
                ksort($discounts);
                foreach ($discounts as $discount) {
                    if ($discount['type'] == 'PERCENTAGE') {
                        $totalOrderValue -= $totalOrderValue * ($discount['value'] / 100);
                    } else {
                        $totalOrderValue -= $discount['value'];
                    }
                }
                $orderLine = [
                    'order_id' => $order->order_id,
                    'order_date_time' => date("c", strtotime($order->order_date)),
                    'total_order_value' => $totalOrderValue,
                    'average_unit_price' => $totalOrderValue / $totalItemsCount,
                    'distinct_unit_count' => $uniqueItemCount,
                    'total_units_count' => $totalItemsCount,
                    'customer_state' => $order->customer->shipping_address->state
                ];
                // writing the above row to file
                fputcsv($fp, $orderLine);

            }
            fclose($fp);
        }
        return $outPutFile;
    }

    /**
     * @inheritDoc
     */
    public function validateConvertedFile() :bool
    {
        return true;
    }

}