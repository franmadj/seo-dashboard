<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

class Opportunity extends Model
{
    use ValidatingTrait;

    protected $rules = [
        'refference' => 'required|string',
        'data' => 'required|string',
        'campaign_id' => 'required|integer',
    ];
    protected $fillable = ['refference',  'campaign_id','data'];
    
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
    
    public function contacts(){
        return $this->hasMany(Contact::class);
    }
    
    public function getData($key){
        
        //var_dump(json_decode($this->data)->domain);exit;
        $data=json_decode($this->data)->$key;
        if(!empty($data)){
            return $data;
        }
        //return $this->data;
        
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
