<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $fillable = ['id', 'note_type', 'entity_id', 'entity_type', 'text'];

    public function contact() {
        return $this->belongsTo(Contact::class,'contact_id',);
    }

}
