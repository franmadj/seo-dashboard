<?php

namespace App\Transformers;

use App\Opportunity;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;

class OpportunitiesTransformer extends Transformer {

    /**
     * A list of all available relations.
     *
     * @var array
     */
    protected $relations = ['contacts'];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = ['contacts'];

    /**
     * Transform the model data into a generic array.
     *
     * @param  Reservation $reservation
     * @return array
     */
    public function transform(Opportunity $opportunity): array {
        return [
            'id' => (int) $opportunity->id,
            'contacts' => $opportunity->contacts,
            'campaign' => $opportunity->campaign->name,
            "domain" => $opportunity->getData('domain'),
            "messaged" => $opportunity->message_state,
            "contactsCount" => $opportunity->contacts->count(),
        ];
    }

}
