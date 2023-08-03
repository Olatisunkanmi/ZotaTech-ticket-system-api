<?php

namespace App\Models;

use App\Helper\Helper;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, HasUlids;

    public $fillable = ['name'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'category_event', 'category_id', 'event_id');
    }

    public function attachEvents(string $events): void
    {
        $this->events()->attach($events);
    }

    public function detachEvents(string $events): void
    {
        $this->events()->detach($events);
    }

}