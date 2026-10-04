<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientPair extends Model
{
  protected $fillable = [
    'box_id',
    'ingredient_a_id',
    'ingredient_b_id',
    'status',
    'description',
    'title'
  ];

  public function box(): BelongsTo
  {
    return $this->belongsTo(Box::class);
  }

  public function ingredientA(): BelongsTo
  {
    return $this->belongsTo(Ingredient::class, 'ingredient_a_id');
  }

  public function ingredientB(): BelongsTo
  {
    return $this->belongsTo(Ingredient::class, 'ingredient_b_id');
  }
}
