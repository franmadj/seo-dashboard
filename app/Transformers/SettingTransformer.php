<?php

namespace App\Transformers;

use App\Setting;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;

class SettingTransformer extends Transformer {

    /**
     * Transform the model data into a generic array.
     *
     * @param  Reservation $reservation
     * @return array
     */
    public function transform(Setting $setting): array {
        $metaValue = json_decode($setting->meta_value,true);

        return [
            'id' => (int) $setting->id,
            'user' => [
                'id' => $setting->user->id,
                'name' => $setting->user->name,
            ],
            "metaKey" => $setting->meta_key,
            "apiKey" => array_get($metaValue, 'apiKey', ''),
            "endPoint" => array_get($metaValue, 'endPoint', ''),
            'queryParam'=>array_get($metaValue, 'queryParam', new \stdClass()),
            
        ];
    }

}
