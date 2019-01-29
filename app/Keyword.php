<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;
use App\Traits\HasImport;

class Keyword extends Model
{
    
    use ValidatingTrait,
        HasImport;

    protected $rules = [
        'word' => 'required|string',
        'campaign_id' => 'required|integer',
    ];
    protected $fillable = ['word',  'campaign_id'];
    
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
   

    public static function make($data) {
        $model = new self;
        
        self::validate($data, $model);
        $model->fill($data);
        $model->save();
        return $model;
    }
    
    public static function importResources(array $data) {
        $file = $data['file'];
        $results=self::getImport($file);
        
      
        foreach ($results as $result) {
            $model = new self;
            $values = ['word' => $result['A'], 'campaign_id' => $data['campaign_id']];
            self::validate($values, $model);
            $model->fill($values);
            $model->save();
        }
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
