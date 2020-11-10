<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailsContact extends Model
{
    protected $fillable = ['email', 'contact_id'];
    
    public function contacts(){
        return $this->belongsToMany(Contact::class);
    }
}
