<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\HasNotes;
use EngineDigital\Note\HasAllNotesAttribute;
use EngineDigital\Note\Note;
use Illuminate\Database\Eloquent\Model;

class FakeUserWithNotes extends Model
{
    use HasNotes;
    use HasAllNotesAttribute;

    protected $table = 'users';
    protected $fillable = ['name'];
    protected $appends = ['all_notes'];
}

class NoteAuthorTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.cache_time', null);
        config()->set('model-notes.load_with', ['author']);

        config()->set('model-notes.author_model', FakeUserWithNotes::class);
        app()->bind('App\\UserResolver', function () {
            return function () {
                return FakeUserWithNotes::first()->id;
            };
        });

        config()->set('model-notes.author_resolver', 'App\\UserResolver');

        $createUser = require __DIR__ . '/create_users_table.php';
        $createUser->up();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();

        $this->user = FakeUserWithNotes::create([
            'name' => 'The Author',
        ]);
    }

    /** @test */
    public function notes_can_eager_load_related_data()
    {
        $anotherUser = FakeUserWithNotes::create([
            'name' => 'Someone Else',
        ]);

        $note = Note::create([
            'note' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            // 'author_id' => $this->user->id, // should be set by the model booted method
            'model_type' => FakeUserWithNotes::class,
            'model_id' => $anotherUser->id,
        ]);

        $first_note = $anotherUser->fresh()->all_notes[0]->toArray();

        // $this->user created the note and should have their details loaded on the notes created on $anotherUser
        $this->assertEquals($this->user->name, $first_note['author']['name']);
    }
}
