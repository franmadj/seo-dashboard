<?php

namespace App\Http\Controllers;

use App\Campaign;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Transformers\CampaignTransformer;
use App\Services\Searches\BingSearch;

class CampaignController extends Controller {
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $campaigns= \Auth::user()->campaigns;
        //$campaigns= \App\User::find(5)->campaigns;
        return Responder::success($campaigns,new CampaignTransformer)->respond();
    }

    /**
     * Generate Bing searches from campaign keywords.
     *
     * @return \Illuminate\Http\Response
     */
    public function searches(Request $request, Campaign $campaign) {
        try {
            $search = (new BingSearch($campaign))->makeSearch();//var_dump((array)json_decode($search));exit;
            return Responder::success($search)->respond();
        }  catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $model = Campaign::make($request->all(), Auth::user()->id);
            return Responder::success($model)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign) {
        return Responder::success($campaign)->respond();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign) {
        try {
            $campaign = Campaign::updateModel($campaign, $request->all());
            return Responder::success($campaign, new CampaignTransformer)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign) {
        try {
            $result = $campaign->delete();
            if ($result) {
                return Responder::success();
            }

            return Responder::error('Campaign was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

}
