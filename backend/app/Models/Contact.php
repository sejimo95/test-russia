<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = ['id', 'name', 'phone'];

    public function deals() {
        return $this->belongsToMany(Deal::class, 'contacts_deals', 'deal_id','contact_id');
    }

    public function notes() {
        return $this->hasMany(Note::class,'contact_id');
    }

}
