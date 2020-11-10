<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Transformers\TemplateTransformer;

class TemplateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $templates = Auth()->user()->templates;
        return Responder::success($templates)->respond();
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $model = Template::make($request->all(), Auth::user()->id);
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
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template) {
        try {
            $model = Template::updateModel($template, $request->all());
            return Responder::success($model, new TemplateTransformer)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template) {
        try {
            $result = $template->delete();
            if ($result) {
                return Responder::success();
            }

            return Responder::error('Template was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

}
