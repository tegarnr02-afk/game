<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
  protected $fillable = ['name', 'condition_label', 'is_locked', 'order', 'face_type', 'icon'];

  protected $casts = [
    'is_locked' => 'boolean',
  ];

  public function ingredients(): HasMany
  {
    return $this->hasMany(Ingredient::class)->orderBy('id');
  }

  public function ingredientPairs(): HasMany
  {
    return $this->hasMany(IngredientPair::class);
  }
}
