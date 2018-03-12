<?php

namespace App\Classes;

use App\Order;
use DHL\Entity\AM\GetQuote;
use DHL\Datatype\AM\PieceType;
use DHL\Client\Web as WebserviceClient;

class DHL
{
    /**
     * @param $postcode
     * @param array $order
     * @return \SimpleXMLElement
     */
    public function calculate($postcode, array $order)
    {
        require_once(__DIR__ . '/../../vendor/alfallouji/dhl_api/init.php');

        // DHL Settings
        //$dhl = $config['dhl'];

        $sample = new GetQuote();

        // Request
        $sample->SiteID = env('DHL_SITE_ID');
        $sample->Password = env('DHL_PASSWORD');
        $sample->MessageTime = date('c');
        $sample->MessageReference = str_random(32);

        // From
        $sample->From->CountryCode = strtoupper(env('DHL_FROM_COUNTRY_CODE', 'SG'));
        $sample->From->Postalcode = env('DHL_FROM_POSTAL_CODE', '339696');
        $sample->From->City = env('DHL_FROM_CITY', 'Singapore');

        // BkgDetails
        foreach($order as $k => $product) {
            $piece = new PieceType();
            $piece->PieceID = $k+1;

            if(!empty($product['shipping_weight'])) {
                $weight = explode(" ", $product['shipping_weight'])[0];
            } elseif(!empty($product['product_weight'])) {
                $weight = explode(" ", $product['product_weight'])[0];
            } else {
                $weight = 1;
            }

            $piece->Weight = $weight;

            $dimensions = $this->getDimensions($product);

            if(!empty($dimensions) && count($dimensions) == 3) {
                $piece->Width = trim($dimensions[0]);
                $piece->Height = trim($dimensions[1]);
                $piece->Depth = trim($dimensions[2]);
            }

            $sample->BkgDetails->addPiece($piece);
        }

        $sample->BkgDetails->PaymentCountryCode = strtoupper(env('DHL_PAYMENT_COUNTRY_CODE', 'US'));
        $sample->BkgDetails->Date = date('Y-m-d');
        $sample->BkgDetails->ReadyTime = 'PT10H30M';
        $sample->BkgDetails->DimensionUnit = 'CM';
        $sample->BkgDetails->WeightUnit = 'KG';

        /*if ($country->is_dutiable) {
            $tva = (Cart::total() * $country->tva) / 100;

            $sample->BkgDetails->IsDutiable = 'Y';
            $sample->Dutiable->DeclaredValue = $price;
            $sample->Dutiable->DeclaredCurrency = 'USD';
        } else {}*/

        $sample->BkgDetails->IsDutiable = 'N';
        $sample->BkgDetails->NetworkTypeCode = 'AL';

        $sample->To->CountryCode = strtoupper(env('DHL_TO_COUNTRY_CODE'));
        $sample->To->Postalcode = $postcode;
        //$sample->To->City = env('DHL_TO_CITY');

        // Call DHL XML API
        $sample->toXML();
        $client = new WebserviceClient('production');
        $xml = $client->call($sample);
        $xmlObject = simplexml_load_string($xml);

        return $xmlObject;
    }

    /**
     * @param $item
     * @return array|bool
     */
    public function getDimensions($item)
    {
        if(!empty($item['shipping_dimension'])) {
            $dimension = $item['shipping_dimension'];
        }
        elseif(!empty($item['product_dimension'])) {
            $dimension = $item['product_dimension'];
        }
        else {
            return false;
        }

        $dimension = trim(str_replace('inches', '', $dimension));

        return explode('x', $dimension);
    }

    /**
     * @param $postal_code
     * @param array $order
     * @return array
     * @throws \Exception
     */
    public function getDhlShipping($postal_code, array $order)
    {
        $xmlObject = $this->calculate($postal_code, $order);

        if (isset($xmlObject->GetQuoteResponse->Note->Condition->ConditionCode)) {
            //throw new \Exception($xmlObject->GetQuoteResponse->Note->Condition->ConditionData);
            return false;
        }

        if (isset($xmlObject->Response->Status->ActionStatus) && $xmlObject->Response->Status->ActionStatus == 'Error') {
            //throw new \Exception($xmlObject->Response->Status->Condition->ConditionData);
            return false;
        }

        $qtdShps = [];

        foreach ($xmlObject->GetQuoteResponse->BkgDetails->QtdShp as $qtdShp) {
            $qtdShps[] = (array) $qtdShp;
        }

        $qtdShps = $this->correctDhlPrice($qtdShps);

        return $qtdShps;
    }

    /**
     * @param array $qtdShps
     * @return array
     */
    public function correctDhlPrice(array $qtdShps)
    {
        array_map(function(&$qtdShp) {
            if(!empty($qtdShp['ShippingCharge'])) {
                $qtdShp['ShippingCharge'] = round($qtdShp['ShippingCharge'], 2);
            }
        }, $qtdShps);

        unset($qtdShp);

        return $qtdShps;
    }
}
