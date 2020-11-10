<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Services\Hunter\Hunter;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use App\Transformers\OpportunitiesTransformer;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favorites(Request $request) { 

        if (in_array(Auth::user()->id, [8, 9,7]) && \App::environment() != 'Local')
            $campaigns = \App\User::find(5)->campaigns->pluck('id')->toArray();
        else
            $campaigns = \Auth::user()->campaigns->pluck('id')->toArray();

        //$campaigns = \Auth::user()->campaigns->pluck('id')->toArray();
        $favorites = Opportunity::whereFavorite(1)->whereIn('campaign_id', $campaigns)->with('contacts')->get();
        return Responder::success($favorites, new OpportunitiesTransformer())->respond();

        return Responder::error();
    }

    /**
     * make existing opportunity to favorite ready to be contacted.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function favorite(Request $request) {
        try {
            $favorites = [];
            $notFound = [];
            if ($request->has('opportunities')) {
                collect($request->get('opportunities'))->each(function($op)use(&$favorites, &$notFound) {
                    if ($opport = Opportunity::whereId($op)->first()) {
                        if ((new Hunter($opport))->makeHunt()) {
                            $opport->favorite = 1;
                            if ($opport->save()) {
                                $favorites[] = $opport->id;
                            }
                        } else {
                            $notFound[] = $op;
                        }
                    }
                });
            }

            return Responder::success(['favorites' => $favorites, 'notFound' => $notFound])->respond();
        } catch (\Exception $e) {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opportunity $opportunity) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request) {
        try {
            if ($request->has('opportunities')) {
                $result = Opportunity::whereIn('id', $request->get('opportunities'))->delete();
                if ($result) {
                    return Responder::success($request->get('opportunities'));
                }
            }

            return Responder::error('Opportunity was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

}
