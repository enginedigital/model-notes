<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\Note;
use Illuminate\Database\Eloquent\Model;

class FakeUser extends Model
{
    protected $table = 'users';
    protected $fillable = ['name'];
}

class NoteUserTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.author_model', FakeUser::class);
        app()->bind('App\\UserResolver', function () {
            return function () {
                return FakeUser::first()->id;
            };
        });

        config()->set('model-notes.author_resolver', 'App\\UserResolver');

        $createUser = require __DIR__ . '/create_users_table.php';
        $createUser->up();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();

        $this->user = FakeUser::create([
            'name' => 'Some User',
        ]);
    }

    /** @test */
    public function notes_can_have_users()
    {
        $note = Note::create([
            'note' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            // 'author_id' => $this->user->id, // should be set by the model booted method
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals($this->user->id, $note->fresh()->author_id);
    }
}
