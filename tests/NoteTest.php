<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\Note;

class NoteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $migration = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $migration->up();
    }

    /** @test */
    public function notes_can_be_created()
    {
        $note = new Note([
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $note->save();

        $this->assertTrue($note->exists());
        $this->assertEquals(config('model-notes.note_default_type'), $note->fresh()->type);
    }

    /** @test */
    public function notes_can_be_queried()
    {
        $existingNote = Note::create([
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $note = Note::create([
            'note' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'model_type' => Note::class,
            'model_id' => $existingNote->id,
        ]);

        $this->assertEquals([1, $existingNote->id], Note::all()->pluck('model_id')->toArray());
        $this->assertEquals(Note::class, get_class($note->model()->first()));
    }

    /** @test */
    public function notes_can_stringified()
    {
        $expected = 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';

        $note = Note::create([
            'note' => $expected,
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals($expected, (string)$note);
    }

    /** @test */
    public function notes_can_only_be_saved_with_matching_type()
    {
        $this->expectException(\InvalidArgumentException::class);

        Note::create([
            'note' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'type' => 'xml',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);
    }
}
