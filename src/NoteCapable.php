<?php

namespace EngineDigital\Note;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface NoteCapable
{
    public function notes(): MorphMany;

    public function setNote(string $note): Note;
}
