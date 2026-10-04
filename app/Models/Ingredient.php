<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
  protected $fillable = ['box_id', 'name', 'icon_key', 'hint_text'];

  public function box(): BelongsTo
  {
    return $this->belongsTo(Box::class);
  }
}
