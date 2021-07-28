<?php

namespace EngineDigital\Note\Tests;

use EngineDigital\Note\Note;
use Illuminate\Database\Eloquent\Model;

class FakeCompany extends Model
{
    protected $table = 'company';
    protected $fillable = ['name'];
}

class NoteTenantTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.tenant_model', FakeCompany::class);

        $createCompany = require __DIR__ . '/create_company_table.php';
        $createCompany->up();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();
    }

    /** @test */
    public function notes_can_have_tenants()
    {
        $company = FakeCompany::create([
            'name' => 'My Company',
        ]);

        $note = Note::create([
            'note' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'tenant_id' => $company->id,
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals($company->id, $note->fresh()->tenant_id);
    }
}
