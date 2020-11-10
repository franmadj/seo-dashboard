<?php

namespace App\Transformers;

use App\Campaign;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;

class TemplateTransformer extends Transformer {

    /**
     * Transform the model data into a generic array.
     *
     * @param  Reservation $reservation
     * @return array
     */
    public function transform(Template $templates): array {
        return [
            'id' => (int) $templates->id,
            'user' => [
                'id' => $templates->user->id,
                'name' => $templates->user->name,
            ],
            "text" => $templates->text,
            "name" => $templates->name,
            "subject" => $templates->subject,

            
        ];
    }

}
