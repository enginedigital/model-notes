<?php

namespace EngineDigital\Note;

use Exception;

/**
 * Handles the logic for using including notes on models
 */
trait HasAllNotesAttribute
{
    protected static function bootHasAllNotesAttribute(): void
    {
        if (class_uses_recursive(HasNotes::class) === false) {
            throw new Exception('HasAllNotesAttribute required the model to also use HasNotes');
        }
    }

    public function getAllNotesAttribute()
    {
        /** @var int|null */
        $cacheTimeInSeconds = config('model-notes.cache_time');

        /** @var string[] */
        $eagerLoad = config('model-notes.load_with', []);

        if (is_null($cacheTimeInSeconds)) {
            return $this->notes()->with($eagerLoad)->get();
        }

        /** @var string */
        $key = sprintf('%s-notes-%s', get_class($this), $this->{$this->getKeyName()});

        return cache()->remember($key, $cacheTimeInSeconds, function () use ($eagerLoad) {
            return $this->notes()->with($eagerLoad)->get();
        });
    }
}
