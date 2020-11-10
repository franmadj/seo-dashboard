<?php

namespace App\Services\Searches;

use App\Campaign;
use App\Setting;
use App\Oopportunity;
use Illuminate\Support\Collection;

class BingSearch {

    protected $bingAccessKey;
    protected $bingEndPoint;
    protected $excludedDomains = [];
    protected $precessedDomains = [];
    protected $buildResult = [];
    protected $campaign;
    protected $domdetailerAccesKey;
    protected $domdetailerEndPoint;
    protected $bingMkt;
    protected $bingSafeSearch;
    protected $bingFreshness;

    public function __construct(Campaign $campaign) { 
        if (($bingDetails = Setting::where('meta_key', 'bing')->where('user_id',\Auth::user()->id)->first()) && ($domDetails = Setting::where('meta_key', 'domdetailer')->where('user_id',\Auth::user()->id)->first())) {
            if (($dataBing = json_decode($bingDetails->meta_value)) &&
                    ($dataDom = json_decode($domDetails->meta_value))) {

                $this->bingAccessKey = isset($dataBing->apiKey) ? $dataBing->apiKey : '';
                $this->bingEndPoint = isset($dataBing->endPoint) ? $dataBing->endPoint : '';
                $this->bingMkt = isset($dataBing->queryParam->Market) ? $dataBing->queryParam->Market : '';
                $this->bingSafeSearch = isset($dataBing->queryParam->Safesearch) ? $dataBing->queryParam->Safesearch : '';
                $this->bingFreshness = isset($dataBing->queryParam->freshness) ? $dataBing->queryParam->freshness : '';

                $this->domdetailerAccesKey = isset($dataDom->apiKey) ? $dataDom->apiKey : '';
                $this->domdetailerEndPoint = isset($dataDom->endPoint) ? $dataDom->endPoint : '';

                $this->buildResult = [];
            } else
                throw new \Exception('No valid api Key');
            $this->campaign = $campaign;
        } else
            throw new \Exception('No valid api Key');
    }

    function areKeysSet() {
        return !empty($this->bingAccessKey) && !empty($this->bingEndPoint) && !empty($this->domdetailerAccesKey) && !empty($this->domdetailerEndPoint);
    }

    function BingWebSearch($query, $offset) {

        $headers = "Ocp-Apim-Subscription-Key: $this->bingAccessKey\r\n";
        $options = array('http' => array(
                'header' => $headers,
                'method' => 'GET'));

        // Perform the Web request and get the JSON response
        $context = stream_context_create($options);
        //var_dump($url . "?q=" . urlencode($query), false, $context);
        $newquery = !empty($this->bingMkt) ? '&mkt=' . $this->bingMkt : '';
        $newquery .= !empty($this->bingSafeSearch) ? '&SafeSearch=' . $this->bingSafeSearch : '';
        $newquery .= !empty($this->bingFreshness) ? '&freshness=' . $this->bingFreshness : '';

        $result = file_get_contents($this->bingEndPoint . "?q=" . urlencode($query) . "&count=50&offset=".$offset . $newquery, false, $context);//var_dump($result);exit;
        //var_dump($url . "?q=" . urlencode($query) . "&count=1000");
        // Extract Bing HTTP headers
        $headers = array();
        foreach ($http_response_header as $k => $v) {
            $h = explode(":", $v, 2);
            if (isset($h[1]))
                if (preg_match("/^BingAPIs-/", $h[0]) || preg_match("/^X-MSEdge-/", $h[0]))
                    $headers[trim($h[0])] = trim($h[1]);
        }

        return array($headers, $result);
    }

    function getOpportunities($refference) {
        $buildResult = [];
        $opportunities = $this->campaign->opportunities()->where('refference', $refference)->whereFavorite(0)->get();
        if ($opportunities->count()) {
            $opportunities->map(function($op)use(&$buildResult) {
                $buildResult[] = array_merge(json_decode($op->data, true), ['id' => $op->id]);
            });
            return collect($buildResult);
        }
        return false;
    }

    function makeOpportunities($refference, $opportunities) {
        $this->campaign->opportunities()->whereFavorite(0)->delete();
        collect($opportunities)->map(function($op)use($refference) {
            $this->campaign->opportunities()->create(['data' => json_encode($op), 'refference' => $refference]);
        });
    }

    function makeSearch() {
        if ($this->areKeysSet()) {
            $terms = $this->campaign->keywords->pluck('word');
            $this->campaign->excludedDomains->pluck('domain')->each(function($domain) {
                $this->excludedDomains[] = domainStrip($domain);
            });


            $refference = md5($terms . implode('', $this->excludedDomains) . $this->bingMkt . $this->bingSafeSearch . $this->bingFreshness);
            $opportunities = $this->getOpportunities($refference);
            if ($opportunities) {
                return $opportunities;
            } elseif (strlen($this->bingAccessKey) == 32 && $terms) { 

                foreach ($terms as $term) {
                    for ($num = 0; $num <= 3; $num++) {
                        $offset=$num*50;

                        list($headers, $json) = $this->BingWebSearch($term, $offset);
                        if ($data = json_decode($json)) {
                            if (isset($data->webPages->value))
                                if ($results = $data->webPages->value) {
                                    $this->filterSearchResult($results);
                                }
                        }
                    }
                }
                if ($this->buildResult) {
                    $this->makeOpportunities($refference, $this->buildResult);
                    return $this->getOpportunities($refference);
                }
                return [];
            }
        }
        throw new \exception("Invalid Bing Search API subscription key!\n");
    }

    function filterSearchResult($results) {
        foreach ($results as $result) {
            $domain = parse_url('http://' . domainStrip($result->displayUrl), PHP_URL_HOST); 
            if (in_array($domain, $this->precessedDomains) || strposa($domain, $this->excludedDomains))
                continue;
            $this->precessedDomains[] = $domain;
            $this->buildResult[] = array_merge(['url' => $result->displayUrl, 'name' => $result->name, 'domain' => $domain], $this->getDomDetailerData($domain) ?: []);
        }
    }

    function getDomDetailerData($domain) {


        return json_decode(file_get_contents($this->domdetailerEndPoint . "?domain=" . $domain . "&app=DomDetailer&apikey=" . $this->domdetailerAccesKey . "&majesticChoice=root"), true);
    }

}
