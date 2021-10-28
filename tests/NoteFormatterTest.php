<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\Note;
use stdClass;

class NoteFormatterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();
    }

    /** @test */
    public function notes_can_be_formatted_by_type_null()
    {
        config()->set('model-notes.note_types.plain', null);

        $content = 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
        $note = Note::create([
            'note' => $content,
            'type' => 'plain',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals($content, $note->getAttributes()['note']);
        $this->assertEquals($content, $note->note);
        $this->assertEquals($content, $note->formatted_note);
        $this->assertEquals($note->note, $note->formatted_note);
    }

    /** @test */
    public function notes_can_be_formatted_by_type_plain()
    {
        config()->set('model-notes.note_types.plain', 'e');

        $note = Note::create([
            'note' => '<p>Hey</p>',
            'type' => 'plain',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals('<p>Hey</p>', $note->note);
        $this->assertStringContainsString('&lt;p&gt;Hey&lt;/p&gt;', $note->formatted_note);
    }

    /** @test */
    public function notes_can_be_formatted_by_type_markdown()
    {
        config()->set('model-notes.note_types.markdown', [\Illuminate\Support\Str::class, 'markdown']);

        $note = Note::create([
            'note' => '*bold*',
            'type' => 'markdown',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals('*bold*', $note->note);
        $this->assertStringContainsString('<p><em>bold</em></p>', $note->formatted_note);
    }

    /** @test */
    public function notes_can_be_formatted_by_type_json()
    {
        config()->set('model-notes.note_types.json', 'json_decode');

        $note = Note::create([
            'note' => '["a", 1, null, true, {}]',
            'type' => 'json',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals('["a", 1, null, true, {}]', $note->note);
        $this->assertEquals(['a', 1, null, true, new stdClass()], $note->formatted_note);
    }

    /** @test */
    public function note_format_will_throw_with_invalid_config()
    {
        config()->set('model-notes.note_types.plain', 'not_a_function');

        $this->expectException(\Exception::class);

        $note = Note::create([
            'note' => 'Content here',
            'type' => 'plain',
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        // this will throw when trying to build `formatted_note`
        $note->toArray();
    }
}
