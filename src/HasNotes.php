<?php

namespace EngineDigital\Note;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasNotes
{
    public function notes(): MorphMany
    {
        return $this->morphMany($this->getNoteModelClassName(), 'model', 'model_type', $this->getModelKeyColumnName())->latest('id');
    }

    public function setNote(string $note): Note
    {
        return $this->notes()->create(['note' => $note]);
    }

    protected function getModelKeyColumnName(): string
    {
        return config('model-notes.model_primary_key_attribute') ?? 'model_id';
    }

    protected function getNoteModelClassName(): string
    {
        return config('model-notes.note_model');
    }

    protected function getNoteModelType(): string
    {
        return array_search(static::class, Relation::morphMap()) ?: static::class;
    }
}
