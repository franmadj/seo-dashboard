<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

class ImapAccount extends Model {

    use ValidatingTrait;

    protected $rules = [
        'domain' => 'required|string',
        'imap' => 'required|string',
        'smtp' => 'required|string',
        'password' => 'required|string',
        'user_id' => 'required|integer',
    ];
    protected $fillable = ['domain', 'user_id', 'imap', 'smtp', 'password'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getPasswordAttribute($value) {

        return decrypt($value);
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = \Crypt::encrypt($value);
    }

    public static function make($data, $userId) {
        $model = new self;
        $data = array_merge($data, [
            'user_id' => $userId,
        ]);
        self::validate($data, $model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public static function updateModel($model, $data) {
        unset($model->rules['user_id']);
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
