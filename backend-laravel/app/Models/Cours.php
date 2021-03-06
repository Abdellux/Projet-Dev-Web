<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $fillable = [
        'titre', 'description', 'niveau', 'duree', 'document',
        'categorie', 'difficulte', 'objectif', 'prerequis',
        'image_cours', 'professeur_id'
    ];
}
