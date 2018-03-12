<?php

namespace App\Classes;

use App\Rate;
use Faker\Provider\UserAgent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Sunra\PhpSimple\HtmlDomParser;
use App\Classes\Zinc\Api;
use GuzzleHttp\Exception\ServerException ;

use AmazonProduct;
use ApaiIO\Operations\Lookup;

class Parser
{
    private $zincKey;
    private $proxy;
    private $attempts;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->zincKey = config('services.zinc.secret_key');
        $this->proxy = config('settings.parser.proxy');
        $this->attempts = config('settings.parser.attempts');

        $handlerStack = \GuzzleHttp\HandlerStack::create();
        $handlerStack->push(\GuzzleHttp\Middleware::retry(function($retry, $request, $response, $reason) {

            if(is_object($response) && $response->getStatusCode() == '200') {
                return false;
            }
            elseif(is_object($response) && $response->getStatusCode() == '404') {
                Log::info('Parse product error with code: '.$response->getStatusCode());
                return false;
            }

            Log::info('Parse product attempt #'.($retry+1));

            return $retry < $this->attempts;
        }));

        $this->client = new \GuzzleHttp\Client(['handler' => $handlerStack, 'verify' => false]);
    }

    /**
     * @param $url
     * @return string
     */
    public function getCode($url)
    {
        $par = explode('/',$url);
        $find_dp = false;
        $asin = '';

        foreach ($par as $item) {
            if($find_dp) {
                $asin = $item;
                break;
            }
            if($item == 'dp' || $item == 'product') {
                $find_dp = true;
            }
        }

        return $asin;
    }

    /**
     * @param $url
     * @return Zinc\Product|bool
     */
    public function getProductFromZinc($url)
    {
        if(!$url) {
            return false;
        }

        if(strpos($url, '/') !== FALSE) {
            $asin = $this->getCode($url);
        } else {
            $asin = $url;
        }

        $zinc = new Api($this->zincKey);
        $product = $zinc->getProduct($asin);

        if(!$product){
            $product = $this->parseAmazonFromAPI($asin);
        } elseif(!$product->price) {
            $_product = $this->parseAmazonFromAPI($asin);

            if($_product && $_product->price) {
                $product->price = $_product->price;
            } else {
                return false;
            }
        }

        if(!$product || !$product->price) {
            return false;
        }

        return $product;
    }

    public function parseNodesName($node) {
        $browse_node = $node['BrowseNode'];
        $node_array = array();
        if(!empty($browse_node[1])) {
            $browse_node = $browse_node[count($browse_node)-1];
        }
        if (!empty($browse_node['Name'])) {
            $node_array[] = $browse_node['Name'];
        }
        if (!empty($browse_node['Ancestors'])) {
            $node_array = array_merge($this->parseNodesName($browse_node['Ancestors']), $node_array);
        }
        return $node_array;
    }
    public function parseNodes($node) {
        $browse_node = $node['BrowseNode'];
        $node_array = array();
        if(!empty($browse_node[1])) {
            $browse_node = $browse_node[count($browse_node)-1];
        }
        if (!empty($browse_node['BrowseNodeId'])) {
            $node_array[] = $browse_node['BrowseNodeId'];
        }
        if (!empty($browse_node['Ancestors'])) {
            $node_array = array_merge($this->parseNodes($browse_node['Ancestors']), $node_array);
        }
        return $node_array;
    }

    /**
     *
     */
    public function parseAmazonFromAPI($url)
    {
        if(!$url) {
            return false;
        }

        if(strpos($url, '/') !== FALSE) {
            $asin = $this->getCode($url);
        } else {
            $asin = $url;
        }

        $is_a_variant = false;

        $response = $this->fetchAmazonAPI($asin);

        if (empty($response) || empty($response['Items']['Item']))
            return false;

        $item = $response['Items']['Item'];
        $image = !empty($item['SmallImage']) ? str_replace('_SL75_', '_SL400_', $item['SmallImage']['URL']) : '';

        // if have ParentASIN will have variations
        if (!empty($item['ParentASIN'])) {
            $parentASIN = $item['ParentASIN'];
        } else {
            $parentASIN = $asin;
        }

        if ($parentASIN != $asin) {
            $parentResponse = $this->fetchAmazonAPI($parentASIN);
            if (!empty($parentResponse) && !empty($parentResponse['Items']['Item']))
                $response = $parentResponse;
            $is_a_variant = true;
            unset($parentResponse);
        }
        $item = $response['Items']['Item'];
        $tmp_parent_asin = $item['ASIN'];

        $get_nodes = $item['BrowseNodes'];
        $nodes = $this->parseNodes($get_nodes);
        $nodeNames = $this->parseNodesName($get_nodes);

        $node_name = '';
        for ($i=count($nodeNames)-1;$i>=0;$i--) {
            $node_name .= $nodeNames[$i].' > ';
        }
        $node_name = trim($node_name,' >');
        echo $node_name;
        echo '<br>';
        echo 'Men\'s Clothing';
        echo ' -- ';
        similar_text($node_name, 'Men\'s Clothing', $percent);
        echo $percent;
        echo '<br>';
        echo 'Women\'s Clothing';
        echo ' -- ';
        similar_text($node_name, 'Women\'s Clothing', $percent);
        echo $percent;
        echo '<br>';
        echo 'Kitchen & Household Items';
        echo ' -- ';
        similar_text($node_name, 'Kitchen & Household Items', $percent);
        echo $percent;
        exit();
        $price = 0;
        if(!empty($item['VariationSummary']['LowestPrice']['Amount']))
            $price = $item['VariationSummary']['LowestPrice']['Amount'] / 100;
        elseif(!empty($item['Offers']['Offer']['OfferListing']['SalePrice']['Amount']) && $item['Offers']['Offer']['OfferAttributes']['Condition'] == 'New')
            $price = $item['Offers']['Offer']['OfferListing']['SalePrice']['Amount'] / 100;
        elseif(!empty($item['Offers']['Offer']['OfferListing']['Price']['Amount']) && $item['Offers']['Offer']['OfferAttributes']['Condition'] == 'New')
            $price = $item['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100;        
        elseif(!empty($item['OfferSummary']['LowestNewPrice']['Amount']))
            $price = $item['OfferSummary']['LowestNewPrice']['Amount'] / 100;
        elseif (!empty($item['ItemAttributes']['ListPrice']['Amount']))
            $price = $item['ItemAttributes']['ListPrice']['Amount'] / 100;

        if(isset($item['OfferSummary']['TotalNew']) && $item['OfferSummary']['TotalNew'] == 0) {
            $price = $item['OfferSummary']['TotalNew'];
        }

        $image = !empty($item['SmallImage']) ? str_replace('_SL75_', '_SL400_', $item['SmallImage']['URL']) : $image;

        if (empty($image)) {
            if (!empty($item['ImageSets']['ImageSet'][0])) {
                $image = str_replace('_SL75_', '_SL400_', $item['ImageSets']['ImageSet'][0]['SmallImage']['URL']);
            }
            if (!empty($item['ImageSets']['ImageSet']['SmallImage'])) {
                $image = str_replace('_SL75_', '_SL400_', $item['ImageSets']['ImageSet']['SmallImage']['URL']);
            }
        }

        $data = array(
            'title' => $item['ItemAttributes']['Title'],
            'id' => $asin,
            'image' => $image,
            'price' => $price,
            'support' => true
        );

        if(!empty($item['ItemAttributes']['ProductTypeName']) && $item['ItemAttributes']['ProductTypeName'] == 'ABIS_EBOOKS') {
            $data['support'] = false;
        }

        $item_dimensions = [];
        if (!empty($item['ItemAttributes']['ItemDimensions'])) {
            $item_dimensions = $this->extractDimensions($item['ItemAttributes']['ItemDimensions']);
        }

        $shipping_dimension = [];
        if (!empty($item['ItemAttributes']['PackageDimensions'])) {
            $shipping_dimension = $this->extractDimensions($item['ItemAttributes']['PackageDimensions']);
        }

        $shipping_weight = !empty($item['ItemAttributes']['PackageDimensions']['Weight']) ? $item['ItemAttributes']['PackageDimensions']['Weight'] / 100 : 1;

        // check if there are any variations
        $variations = $variation_dimensions = [];
        $default_selected = false;

        if (!empty($item['Variations']) && $item['Variations']['TotalVariations'] >= 1) {
            $variation_dimensions = !empty($item['Variations']['VariationDimensions']['VariationDimension']) ? $item['Variations']['VariationDimensions']['VariationDimension'] : ['variant'];
            $variation_dimensions_keys = [];

            if (!is_array($variation_dimensions))
                $variation_dimensions = [$variation_dimensions];

            foreach ($variation_dimensions as $dimension) {
                $dimension = $this->createSlug($dimension, '_');
                $variation_dimensions_keys[] = $dimension;
            }

            $variation_dimensions = array_combine($variation_dimensions_keys, $variation_dimensions);
            foreach ($variation_dimensions as $key=>$value)
                $$key = ['title'=>'', 'values'=>[], 'cross_dimensions'=>[]];

            if($item['Variations']['TotalVariations'] == 1) {
                $tmp = $item['Variations']['Item'];
                $item['Variations']['Item'] = array($tmp);
            }
            foreach ($item['Variations']['Item'] as $variant) {
                $variation_key = [];
                
                if (!empty($variant['VariationAttributes']['VariationAttribute'])) {
                    $variant_attributes = count($variation_dimensions) > 1 ? $variant['VariationAttributes']['VariationAttribute'] : $variant['VariationAttributes'];

                    foreach ($variant_attributes as $attribute) {
                        $key = $this->createSlug($attribute['Name'], '_');
                        $value_slug = $this->createSlug($attribute['Value'], '_');
                        $$key['values'][$value_slug] = $attribute['Value'];

                        $$key['title'] = $attribute['Name'];

                        // setup cross dimensions
                        foreach ($variant_attributes as $cross) {
                            $cross_key = $this->createSlug($cross['Name'], '_');
                            if ($cross_key == $key)
                                continue;

                            $cross_value_slug = $this->createSlug($cross['Value'], '_');

                            $$key['cross_dimensions'][$cross_key][$cross_value_slug] =  $cross['Value'];
                        }

                        $variation_key[] = $value_slug;
                    }
                }
                $price = 0;
                if (!empty($variant['Offers']['Offer']['OfferListing']['SalePrice']['Amount']))
                    $price = $variant['Offers']['Offer']['OfferListing']['SalePrice']['Amount'] / 100;
                elseif (!empty($variant['Offers']['Offer']['OfferListing']['Price']['Amount']))
                    $price = $variant['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100;
                elseif (!empty($variant['ItemAttributes']['ListPrice']['Amount']))
                    $price = $variant['ItemAttributes']['ListPrice']['Amount'] / 100;

                $status = 'new';
                if($price == 0 && $variant['ASIN'] != $tmp_parent_asin) {
                    $tmp_used =  $this->fetchAmazonAPI($variant['ASIN']);
                    if (!empty($tmp_used) && !empty($tmp_used['Items']['Item'])) {
                        $tmp_used_item = $tmp_used['Items']['Item'];

                        if (!empty($tmp_used_item['OfferSummary']['LowestUsedPrice']['Amount'])) {
                            $status = 'used';
                            $price = $tmp_used_item['OfferSummary']['LowestUsedPrice']['Amount'] / 100;
                        }
                        elseif(!empty($item['VariationSummary']['LowestPrice']['Amount']))
                            $price = $item['VariationSummary']['LowestPrice']['Amount'] / 100;
                        elseif(!empty($item['OfferSummary']['LowestNewPrice']['Amount']))
                            $price = $item['OfferSummary']['LowestNewPrice']['Amount'] / 100;
                        elseif (!empty($item['ItemAttributes']['ListPrice']['Amount']))
                            $price = $item['ItemAttributes']['ListPrice']['Amount'] / 100;
                    }
                }

                $image = !empty($variant['SmallImage']) ? str_replace('_SL75_', '_SL400_', $variant['SmallImage']['URL']) : '';
                if (empty($image)) {
                    if (!empty($variant['ImageSets']['ImageSet'][0])) {
                        $image = str_replace('_SL75_', '_SL400_', $variant['ImageSets']['ImageSet'][0]['SmallImage']['URL']);
                    }
                    if (!empty($variant['ImageSets']['ImageSet']['SmallImage'])) {
                        $image = str_replace('_SL75_', '_SL400_', $variant['ImageSets']['ImageSet']['SmallImage']['URL']);
                    }
                }

                $swatch_image = str_replace('_SL400_', '_SL50_', $image);

                $title = !empty($variant['ItemAttributes']['Title']) ? $variant['ItemAttributes']['Title'] : $item['ItemAttributes']['Title'];

                $variant_data = array(
                    'id' => $variant['ASIN'],
                    'image' => $image,
                    'swatch_image' => $swatch_image,
                    'price' => (float)$price,
                    'title' => $title,
                    'status' => $status
                );

                sort($variation_key);

                $variations[implode('-', $variation_key)] = $variant_data;

                if ($variant['ASIN'] == $asin) {                    
                    $data['image'] = $image;
                    $data['price'] = $price;
                    
                    if (!empty($variant['VariationAttributes'])) {
                        foreach ($variant_attributes as $attr) {
                            $key = $this->createSlug($attr['Name'], '_');
                            $data[$key] = $attr['Value'];
                        }
                    }

                    if (!empty($variant['ItemAttributes']['ItemDimensions']))
                        $item_dimensions = $this->extractDimensions($variant['ItemAttributes']['ItemDimensions']);

                    if (!empty($variant['ItemAttributes']['PackageDimensions']))
                        $shipping_dimension = $this->extractDimensions($variant['ItemAttributes']['PackageDimensions']);

                    $shipping_weight = !empty($variant['ItemAttributes']['PackageDimensions']['Weight']) ? $variant['ItemAttributes']['PackageDimensions']['Weight'] / 100 : 1;

                    $default_selected = true;
                }
            }
        }

        if (!$default_selected && !empty($item['Variations']) && count($item['Variations']['Item'])) {
            $default_variant = reset($item['Variations']['Item']);

            $image = !empty($default_variant['SmallImage']) ? str_replace('_SL75_', '_SL400_', $default_variant['SmallImage']['URL']) : '';
            if (empty($image)) {
                if (!empty($default_variant['ImageSets']['ImageSet'])) {
                    $image = str_replace('_SL75_', '_SL400_', $default_variant['ImageSets']['ImageSet'][0]['SmallImage']['URL']);
                }
            }

            $swatch_image = str_replace('_SL400_', '_SL50_', $image);

            if (!empty($default_variant['Offers']['Offer']['OfferListing']['Price']['Amount'])) {
                $data['price'] = $default_variant['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100;
            }

            if (!empty($default_variant['VariationAttributes'])) {
                $variant_attributes = count($variation_dimensions) > 1 ? $default_variant['VariationAttributes']['VariationAttribute'] : $default_variant['VariationAttributes'];

                foreach ($variant_attributes as $attr) {
                    $key = $this->createSlug($attr['Name'], '_');
                    $data[$key] = $attr['Value'];
                }
            }

            if (!empty($default_variant['ItemAttributes']['ItemDimensions']))
                $item_dimensions = $this->extractDimensions($default_variant['ItemAttributes']['ItemDimensions']);

            if (!empty($default_variant['ItemAttributes']['PackageDimensions']))
                $shipping_dimension = $this->extractDimensions($default_variant['ItemAttributes']['PackageDimensions']);

            $shipping_weight = !empty($default_variant['ItemAttributes']['PackageDimensions']['Weight']) ? $default_variant['ItemAttributes']['PackageDimensions']['Weight'] / 100 : 1;
        }

        $data['details'] = array(
            'product_dimension' => implode(' x ', $item_dimensions),
            'shipping_dimension' => implode(' x ', $shipping_dimension),
            'shipping_weight' => $shipping_weight,
            'product_weight' => $shipping_weight,
        );

        /*
        $data['colors'] = isset($data['colors']) ? (array) $data['colors'] : array();
        $data['confs'] = isset($data['confs']) ? (array) $data['confs'] : array();
        $data['sizes'] = isset($data['sizes']) ? (array) $data['sizes'] : array();
        $data['styles'] = isset($data['styles']) ? (array) $data['styles'] : array();
        */

        $data['variation_matrix'] = $variations;
        $data['variations'] = [];
        foreach ($variation_dimensions as $key=>$value)
            $data['variations'][$key] = $$key; 

        ksort($data['variations']);

        $data['dimensions'] = !empty($data['details']['shipping_dimension']) ? explode(' x ', $data['details']['shipping_dimension']) : [];
        $data['dimensionsAsString'] = !empty($data['details']['shipping_dimension']) ? $data['details']['shipping_dimension'] : '';
        $data['weight'] = !empty($data['details']['shipping_weight']) ? $data['details']['shipping_weight'] : '';
        $data['weightGram'] = !empty($data['weight']) ? round($data['weight'] * config('settings.units.pound_eq_gram'), 2) : '';

        $data['nodes'] = $nodes;

        return (object)$data;
    }

    public function fetchAmazonAPI($asin)
    {
        $cache = 'cache/'.$asin.'.txt';
        
        if (Storage::exists($cache)) {
            $time = Storage::lastModified($cache);
            $response = Storage::get($cache);

            if ($time < strtotime('1 hour ago') || empty($response))
                Storage::delete($cache);
            else {
                return unserialize($response);
            }
        }

        $lookup = new Lookup();

        $lookup->setItemId($asin);
        $lookup->setResponseGroup(['Images', 'ItemAttributes', 'Offers', 'Variations', 'VariationOffers', 'BrowseNodes']);
        $lookup->setIdType('ASIN');
        $success = false;
        while (!$success) {
            try {
                $response = AmazonProduct::run($lookup);
                $success = true;
            } catch (ServerException $e) {
            }
        }

        Storage::put($cache, serialize($response));
        return $response;
    }


    /**
     * @param $url
     * @return array|bool
     */
    public function parseAmazon($url)
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'text/html',
                    'User-Agent' => UserAgent::userAgent(),
                ],
                'proxy' => ($this->proxy ? $this->proxy : null)
            ]);

            $text = (string) $response->getBody();
            $html = HtmlDomParser::str_get_html($text);

            if($html) {

                $confs = [];
                $colors = [];
                $sizes = [];
                $styles = [];
                $details = [
                    'shipping_dimension' => '',
                    'product_dimension' => '',
                    'shipping_weight' => '',
                    'product_weight' => '',
                ];

                if(!$img = $html->find('#imgTagWrapperId', 0)) {
                    return false;
                }

                $img = $img->find('img', 0)->src;
                $title = trim($html->find('#productTitle', 0)->plaintext);
                $price = trim($html->find('#priceblock_ourprice', 0)->plaintext);

                if(strpos($price, '-')) {
                    $price = trim(explode('-', $price)[0]);
                }

                $clearPrice = str_replace('$', '', $price);

                if($colorsHtml = $html->find('#variation_color_name', 0)) {
                    $colorsHtml = $colorsHtml->find('img');
                }

                if($confsHtml = $html->find('#variation_configuration', 0)) {
                    $confsHtml = $confsHtml->find('.a-size-base');
                }

                $detailKey = '';

                if($detailsHtml = $html->find('#productDetails_detailBullets_sections1', 0)) {
                    $detailsHtml = $detailsHtml->find('tr');
                    $detailKey = 'td';
                }
                elseif($detailsHtml = $html->find('#productDetails_techSpec_section_2', 0)) {
                    $detailsHtml = $detailsHtml->find('tr');
                    $detailKey = 'td';
                }
                elseif($detailsHtml = $html->find('#detailBullets_feature_div', 0)) {
                    $detailsHtml = $detailsHtml->find('li');
                    $detailKey = 'span';
                }

                if($sizesHtml = $html->find('#variation_size_name', 0)) {
                    $sizesHtml = $sizesHtml->find('.a-size-base');
                }

                if($stylesHtml = $html->find('#variation_style_name', 0)) {
                    $stylesHtml = $stylesHtml->find('li');
                }

                if($stylesHtml) {
                    foreach ($stylesHtml as $v) {
                        $styles[] = $v->plaintext;
                    }
                }

                if($sizesHtml) {
                    foreach ($sizesHtml as $v) {
                        $sizes[] = $v->plaintext;
                    }
                }

                if($colorsHtml) {
                    foreach ($colorsHtml as $v) {
                        $colors[$v->alt] = $v->src;
                    }
                }

                if($confsHtml) {
                    foreach ($confsHtml as $v) {
                        $confs[] = $v->plaintext;
                    }
                }

                if($detailsHtml) {
                    foreach ($detailsHtml as $v) {
                        if(stripos($v->plaintext, 'Shipping Dimensions') !== FALSE) {
                            $details['shipping_dimension'] = $this->getDetailData($v, $detailKey);
                        }

                        if(stripos($v->plaintext, 'Product Dimensions') !== FALSE) {
                            $details['product_dimension'] = $this->getDetailData($v, $detailKey);
                        }

                        if(stripos($v->plaintext, 'Shipping Weight') !== FALSE) {
                            $details['shipping_weight'] = $this->getDetailData($v, $detailKey);
                        }

                        if(stripos($v->plaintext, 'Product Weight') !== FALSE) {
                            $details['product_weight'] = $this->getDetailData($v, $detailKey);
                        }
                    }
                }

                return [
                    'image' => $img,
                    'title' => $title,
                    'price' => $price,
                    'clearPrice' => $clearPrice,
                    'colors' => $colors,
                    'confs' => $confs,
                    'details' => $details,
                    'sizes' => $sizes,
                    'styles' => $styles,
                ];
            }

            return false;
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('Parse product error: ' . $e->getMessage());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Parse product error: ' . $e->getMessage());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Log::error('Parse product error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Parse product error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * @param $html
     * @param $detailKey
     * @return string
     */
    public function getDetailData($html, $detailKey)
    {
        if($detailKey == 'span') {
            $str = $html->find('span', 0)->find('span', 1)->plaintext;
        }
        elseif($detailKey == 'td') {
            $str = $html->find('td', 0)->plaintext;
        }

        if($pos = strpos($str, "(")) {
            $str = substr($str, 0, ($pos - 1));
        }

        return trim($str);
    }

    private function createSlug($str, $delimiter = '-'){
        
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    
    } 

    private function extractDimensions($dimensions)
    {
        $item_dimensions = [];

        if (!empty($dimensions)) {
            $item_dimensions[] = !empty($dimensions['Length']) ? $dimensions['Length'] / 100 : 1;
            $item_dimensions[] = !empty($dimensions['Width']) ? $dimensions['Width'] / 100 : 1;
            $item_dimensions[] = !empty($dimensions['Height']) ? $dimensions['Height'] / 100 : 1;
        }

        return $item_dimensions;
    }
}