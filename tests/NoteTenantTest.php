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
    public $company;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('model-notes.tenant_model', FakeCompany::class);
        app()->bind('App\\TenantResolver', function () {
            return function () {
                return FakeCompany::first()->id;
            };
        });

        config()->set('model-notes.tenant_resolver', 'App\\TenantResolver');

        $createCompany = require __DIR__ . '/create_company_table.php';
        $createCompany->up();

        $createNotes = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        $createNotes->up();

        $this->company = FakeCompany::create([
            'name' => 'My Company',
        ]);
    }

    /** @test */
    public function notes_can_have_tenants()
    {
        $note = Note::create([
            'note' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            // 'tenant_id' => $this->company->id, // should be set by the model booted method
            'model_type' => Note::class,
            'model_id' => 1,
        ]);

        $this->assertEquals($this->company->id, $note->fresh()->tenant_id);
    }
}
