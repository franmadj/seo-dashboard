<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

class Setting extends Model {

    use ValidatingTrait;

    protected $rules = [
        'meta_key' => 'required|string',
        'user_id' => 'required|integer',
    ];
    protected $fillable = ['meta_key', 'meta_value', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function make($data, $userId) {
        $setting = new self;
        $data = array_merge($data, [
            'user_id' => $userId,
        ]);
        self::validate($data, $setting);
        $values = [];
        $values['endPoint'] = array_get($data, 'endPoint') ?: '';
        $values['apiKey'] = array_get($data, 'apiKey') ?: '';
        $values['queryParam'] = array_get($data, 'queryParam') ?: '';
        Setting::where('meta_key', $data['meta_key'])->where('user_id', \Auth::User()->id)->delete();
        $setting = Setting::create(['meta_key' => $data['meta_key'], 'user_id' => $data['user_id'], 'meta_value' => json_encode($values)]);
        //$setting->save();
        return $setting;
    }

    private static function validate($data, $model) {
        $validator = Validator::make($data, $model->rules);
        if ($validator->fails()) {
            throw new ValidationException($validator, $model);
        }
    }

}
