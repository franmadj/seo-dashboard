<?php

namespace App\Http\Controllers;

use App\ExcludedDomain;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;

class ExcludedDomainController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $model = ExcludedDomain::make($request->all());
            return Responder::success($model)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Import excluded domains.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Import(Request $request) {
        try {
            $model = ExcludedDomain::importResources($request->all());
            return Responder::success($model)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExcludedDomain  $excludedDomain
     * @return \Illuminate\Http\Response
     */
    public function edit(ExcludedDomain $excludedDomain) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExcludedDomain  $excludedDomain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExcludedDomain $excludedDomain) {
        try {
            $excludedDomain = ExcludedDomain::updateModel($excludedDomain, $request->all());
            return Responder::success($excludedDomain)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExcludedDomain  $excludedDomain
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExcludedDomain $excludedDomain) {
        try {
            $result = $excludedDomain->delete();
            if ($result) {
                return Responder::success();
            }

            return Responder::error('Keyword was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

}
