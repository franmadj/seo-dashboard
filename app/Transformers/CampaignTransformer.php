<?php

namespace App\Transformers;

use App\Campaign;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;

class CampaignTransformer extends Transformer {

    /**
     * Transform the model data into a generic array.
     *
     * @param  Reservation $reservation
     * @return array
     */
    public function transform(Campaign $campaign): array {
        return [
            'id' => (int) $campaign->id,
            'user' => [
                'id' => $campaign->user->id,
                'name' => $campaign->user->name,
            ],
            "url" => $campaign->url,
            "name" => $campaign->name,
            
        ];
    }

}
