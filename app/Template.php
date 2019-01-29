<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

class Template extends Model
{
    use ValidatingTrait;

    protected $rules = [
        'text' => 'required|string',
        'subject' => 'required|string',
        'name'=> 'required|string',
        'user_id' => 'required|integer',
    ];
   
    protected $fillable = ['name', 'user_id','text','subject'];
    
    public function user(){
        return $this->belongsTo(User::class);
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
