<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $table = 'deals';
    protected $fillable = ['id', 'name'];

    public function contacts() {
        return $this->belongsToMany(Contact::class, 'contacts_deals', 'deal_id','contact_id',);
    }

}
