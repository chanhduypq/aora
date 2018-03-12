<?php

namespace App\Http\Controllers;

use App\Classes\Parser;
use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\ProductsRecommendation;
use App\Blacklist;
use Symfony\Component\HttpFoundation\Session\Session;

class PagesController extends Controller
{
    public $proxyAuth = 'galvin24x7:egor99';
    public $via_proxy = false;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getResult(Request $request)
    {
        if(!$request->has('link')) {
            return redirect()->route('pages.home');
        }

        $url = $request->get('link');
        $parser = new Parser();

        if(!$id = $parser->getCode($url)) {
            return redirect()->route('pages.home');
        }

        return redirect()->route('pages.result.byId', ['id' => $id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function result($id)
    {
        if(!$id) {
            return redirect()->route('pages.home');
        }

        $parser = new Parser();
        $product = $parser->parseAmazonFromAPI($id);

        if($product) {
            session()->forget('variations');
            if (isset($product->variations)) {
                session()->put('variations', $product->variations);
            }
        } else {
            return redirect()->route('pages.home');
        }

        $product->in_blacklist = false;
        if(!empty($product->nodes)) {
            $blacklist = new Blacklist();
            $blacklists = $blacklist->getActiveBlacklist();
            if(!empty($blacklists)) {
                foreach ($product->nodes as $node) {
                    if (in_array($node, $blacklists)) {
                        $product->in_blacklist = true;
                        break;
                    }
                }
            }
        }
        $review_html = $this->curl_execute($product->review_url);
        
        return view('pages.result', ['product' => $product, 'comments' => $this->getComments($review_html)]);
    }
    
    private function getComments($content){
        require_once 'simple_html_dom.php';
        $html_base = new \simple_html_dom();
        $html_base->load($content);
        
        $node = $html_base->find("#cm-cr-dp-review-list",0);
        $comments = array();
        $comment = array('avatar' => '', 'name' => '', 'time' => '', 'rating' => '', 'title' => '', 'title_url' => '', 'content' => '');
        $nodes = $node->find("div[class='a-section review']");
        foreach ($nodes as $node) {
            $comment['avatar'] = $node->find(".a-profile-avatar", 0)->find('img', 1)->attr['src'];
            $comment['name'] = $node->find('.a-profile-name', 0)->plaintext;
            $comment['time'] = $node->find('span[data-hook="review-date"]', 0)->plaintext;
            $comment['title'] = $node->find('a[data-hook="review-title"]', 0)->plaintext;
            $comment['title_url'] = $node->find('a[data-hook="review-title"]', 0)->attr['href'];

            $temp = $node->find("div[class='a-row']", 1);

            $a = $temp->find('a', 0);
            if($a->find("i", 0)){
                $comment['rating_i_class'] = $a->find("i", 0)->attr['class'];
                $comment['rating_i_html'] = $a->find("i", 0)->plaintext;
            }
            else{
                $comment['rating_i_class'] = '';
                $comment['rating_i_html'] = '';
            }
            
            if(isset($a->attr['title'])){
                $comment['rating_title'] = $a->attr['title'];
            }
            else{
                $comment['rating_title'] = '';
            }
            
            $comment['rating_href'] = $a->attr['href'];

            $a = $temp->find('a[data-hook="review-title"]', 0);
            if ($a) {
                $comment['title_html'] = $a->plaintext;
                $comment['title_href'] = $a->attr['href'];
            } else {
                $comment['title_html'] = '';
                $comment['title_href'] = '';
            }


            $temp = explode("|", $node->find("div[class='a-row a-spacing-mini review-data review-format-strip']", 0)->plaintext);
            $comment['string1'] = $temp[0];
            $comment['string2'] = isset($temp[1]) ? $temp[1] : '';
            $comment['string3'] = isset($temp[2]) ? $temp[2] : '';

            $comment['content'] = $node->find('div[data-hook="review-collapsed"]', 0)->plaintext;
            $comments[] = $comment;
        }
        
        return $comments;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $productsRecommendation = ProductsRecommendation::orderBy('order_by')->get();
        return view('pages.home', [ 'productsRecommendation'=> $productsRecommendation]);
    }

    public function reset()
    {
        Product::where('order_id', '>', 3)->delete();
        Order::where('id', '>', 3)->delete();

        die('done');
    }
    

    private function curl_execute($url, $json = false, $referer = false, $count = 0) {

        $headers = array();
        $headers[] = "Accept-Encoding: gzip, deflate";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
        if ($json != false) {
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Accept: application/json";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
        } else {
            $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        }
        $headers[] = "Cache-Control: max-age=0";
        $headers[] = "Connection: keep-alive";
        if ($referer != false) {
            $headers[] = "Referer: " . $referer;
        }

        if (strrpos($url, 'shopee.sg/api/v1/items') !== false) {
            $headers[] = "X-Csrftoken: Xg7jJJwJ4r6fcrtPDchtGilnpfaAB4YO";
            $headers[] = "Cookie: _ga=GA1.2.680186883.1519700856; _gid=GA1.2.1946785702.1519700856; cto_lwid=6b9cee5e-f4f1-41c6-bdb7-9a3648cb988c; csrftoken=Xg7jJJwJ4r6fcrtPDchtGilnpfaAB4YO; __BWfp=c1519700860546xa2fcd5d59; SPC_IA=-1; SPC_U=-; SPC_EC=-; bannerShown=true; SPC_SC_TK=; UYOMAPJWEMDGJ=; SPC_SC_UD=; SPC_F=zRjUetudjMWMgmUmr3f8Tij0vF6L3p0I; REC_T_ID=5c6c2e48-1b6b-11e8-9b1a-1866dab29c0a; SPC_T_ID=\"99jVmjgK9KZL0SnPMX/yuwLLv9M3sEGDo+J3VZT9ZSQx3lifdMmK2MmdqjtqdRttt3ZgPL+lyYVOwmvMZ1z5kZsGi/X9Sfz54Vps8e6Eq1w=\"; SPC_SI=qqdhd4n2jlz4i9124ah7o2wr8m4gaafz; SPC_T_IV=\"Eqx+GcOke9cn5Sl3jATR4A==\"; _gat=1";
        }


        $ch = curl_init();

        //check use proxies
        if ($this->via_proxy) {
            curl_setopt($ch, CURLOPT_PROXY, 'http://' . $this->getProxy());
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxyAuth);
        }
        //post json data
        if ($json != false) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $content = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // print_r("status: ".$status."\n");

        if (($status != 200 && $status != 404) || trim($content) == '' || (strpos($url, 'abc') !== false && strpos($content, '</html>') !== false) && $count < 4) {
            sleep(1);
            $count++;
            return $this->curl_execute($url, $json, $referer, $count);
        }

        return $content;
    }
    private function getProxy() {
        $f_contents = file("proxies.txt");
        $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
        return $line;
    }

}
