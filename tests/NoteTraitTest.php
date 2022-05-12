<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\HasNotes;
use Illuminate\Database\Eloquent\Model;

class AnotherFakeCompany extends Model
{
    use HasNotes;
    protected $table = 'company';
    protected $fillable = ['name'];
}

class TenantResolver
{
    public function __invoke()
    {
        return '123';
    }
}

class NoteTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.tenant_model', AnotherFakeCompany::class);
        config()->set('model-notes.tenant_resolver', TenantResolver::class);

        $createCompany = require __DIR__ . '/create_company_table.php';
        $createCompany->up();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();
    }

    /** @test */
    public function notes_can_be_saved_on_models()
    {
        $company = AnotherFakeCompany::create([
            'name' => 'My Company',
        ]);

        $expected = 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.';

        $company->setNote($expected);

        $this->assertEquals($company->notes()->get()->pluck('note')->toArray(), [$expected]);
    }

    /** @test */
    public function notes_can_be_saved_on_models_with_group()
    {
        $company = AnotherFakeCompany::create([
            'name' => 'My Company',
        ]);

        $note = [
            'note' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.',
            'group' => 'note_group',
        ];

        $company->setNoteWithGroup($note);

        $this->assertEquals($company->notes()->first()->only('note', 'group'), $note);
    }
}
