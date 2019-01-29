<?php

namespace App\Http\Controllers;

use App\ImapAccount;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use Illuminate\Support\Facades\Auth;

class ImapAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imapAccounts=Auth::user()->imapAccounts;
        return Responder::success($imapAccounts)->respond();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $model = ImapAccount::make($request->all(), Auth::user()->id);
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
     * @param  \App\ImapAccount  $imapAccount
     * @return \Illuminate\Http\Response
     */
    public function show(ImapAccount $imapAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ImapAccount  $imapAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(ImapAccount $imapAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ImapAccount  $imapAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImapAccount $imapAccount)
    {
        try {
            $model = ImapAccount::updateModel($imapAccount, $request->all());
            return Responder::success($model)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ImapAccount  $imapAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImapAccount $imapAccount)
    {
        try {
            $result = $imapAccount->delete();
            if ($result) {
                return Responder::success();
            }

            return Responder::error('Account was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }
}
