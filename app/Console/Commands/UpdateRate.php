<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate:update';


    const ADDITIONAL_PERC = 5.7;


    const RATE_UPDATE_SOURCE = 'https://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rate update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        $url = self::RATE_UPDATE_SOURCE;

        try {
            $xml = file_get_contents($url, false, $context);
            $xml = simplexml_load_string($xml);

            if(!$xml) {
                throw new \LogicException('No rate xml');
            }

            foreach ($xml->resources->resource as $item) {
                if((string)$item->field[0] !== 'USD/SGD') {
                    continue;
                }

                $toAmazonExchangeRate = (float)$item->field[1] + ((float)$item->field[1] * (float)self::ADDITIONAL_PERC / 100);

                $data = [
                    'name' => (string)$item->field[0],
                    'price' => $toAmazonExchangeRate,
                    'utctime' => date('Y-m-d H:i', strtotime((string)$item->field[5])),
                ];

                DB::table('rates')->insert($data);
            }
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }

    }
}
