<?php

namespace App\Services\Hunter;

use App\Opportunity;
use App\Setting;
use App\Contact;
use Illuminate\Support\Collection;

class Hunter {

    protected $hunterAccessKey;
    protected $hunterEndPoint;
    protected $opportunity;
    protected $campaign;

    public function __construct(Opportunity $opportunity) {
        if (($hunterDetails = Setting::where('meta_key', 'hunter')->where('user_id',\Auth::user()->id)->first())) {
            if (($data = json_decode($hunterDetails->meta_value))) {

                $this->hunterAccessKey = isset($data->apiKey) ? $data->apiKey : '';
                $this->hunterEndPoint = isset($data->endPoint) ? $data->endPoint : '';


                $this->buildResult = [];
            } else
                throw new \Exception('No valid api Key');
            $this->opportunity = $opportunity;
            $this->campaign = $opportunity->campaign;
        } else
            throw new \Exception('No valid api Key');
    }

    function isKeysSet() {
        return !empty($this->hunterAccessKey) && !empty($this->hunterEndPoint);
    }

    function getHunterContacts($domain) {



        $result = file_get_contents($this->hunterEndPoint . "?domain=" . $domain . "&api_key=" . $this->hunterAccessKey);


        return json_decode($result);
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

    function makeHunt() {
        if ($this->isKeysSet()) {
            $domain = json_decode($this->opportunity->data)->domain;
            $addedDomains = [];
            if ($domain) {
                $contacts = $this->getHunterContacts($domain);
                if (!empty($contacts->data->emails)) {
                    foreach ($contacts->data->emails as $email) {

                        if (!in_array($email->value, $addedDomains))
                            Contact::create(['opportunity_id' => $this->opportunity->id, 'contact_data' => json_encode($email)]);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    function filterSearchResult($results) {
        foreach ($results as $result) {
            $domain = parse_url('http://' . domainStrip($result->displayUrl), PHP_URL_HOST); //var_dump($domain);
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
