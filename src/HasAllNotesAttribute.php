<?php

namespace EngineDigital\Note;

use Exception;

/**
 * Handles the logic for using including notes on models
 */
trait HasAllNotesAttribute
{
    protected static function bootHasNotesAttribute(): void
    {
        if (class_uses_recursive(HasNotes::class) === false) {
            throw new Exception('HasNotesAttribute required the model to also use HasNotes');
        }
    }

    public function getAllNotesAttribute()
    {
        /** @var int|null */
        $cacheTimeInSeconds = config('model-notes.cache_time');

        return $cacheTimeInSeconds ? cache()->remember($this->{$this->getKeyName()} . '-notes', $cacheTimeInSeconds, function () {
            return $this->notes()->get();
        }) : $this->notes()->get();
    }
}
