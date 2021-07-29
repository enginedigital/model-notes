<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\Note;

class NoteEncryptionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.encrypt_notes', true);

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();
    }

    /** @test */
    public function notes_can_be_encrypted()
    {
        $content = 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
        $note = Note::create([
            'note' => $content,
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertNotEquals($content, $note->getAttributes()['note']);
        $this->assertEquals($content, $note->note);
    }
}
