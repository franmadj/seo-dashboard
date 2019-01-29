<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

class Contact extends Model {

    use ValidatingTrait;

    protected $rules = [
        'contact_data' => 'required|string',
        'opportunity_id' => 'required|integer',
    ];
    protected $fillable = ['contact_data', 'opportunity_id', 'state'];

    public function opportunity() {
        return $this->belongsTo(Opportunity::class);
    }
    
    public function emails() {
        return $this->hasMany(Email::class);
    }

    public static function emailSent($idContact, $action = 'sent') {
        $contact = self::find($idContact);
        $contact->message_state = $action;
        $contact->opportunity->message_state = $action;
        $contact->opportunity->save();
        return $contact->save();

    }

    public static function make($data) {
        $model = new self;

        self::validate($data, $model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public static function updateModel($model, $data) {
        unset($model->rules['campaign_id']);
        self::validate($data, $model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    private static function validate($data, $model) {
        $validator = Validator::make($data, $model->rules);
        if ($validator->fails()) {
            throw new ValidationException($validator, $model);
        }
    }

}
