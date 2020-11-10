<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model {

    protected $fillable = ['sender', 'recipient', 'subject', 'body','contact_id','type'];
    
    public function Contact() {
        return $this->belongsTo(Contact::class);
    }
    
    

}
