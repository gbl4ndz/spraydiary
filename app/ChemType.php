<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChemType extends Model
{
    public $timestamps = false;
    protected $table = 'chemtypes';
    protected $fillable = ['name'];

    public function chemicals()
    {
        return $this->hasMany(Chemical::class, 'chem_type');
    }
}
