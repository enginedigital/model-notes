<?php

namespace EngineDigital\Note;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InvalidArgumentException;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
        'tenant_id',
        'note',
        'type',
        'model_type',
        'model_id',
    ];

    // protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function tenant(): BelongsTo
    {
        $tenantClass = config('model-notes.tenant_model');

        if (! $tenantClass) {
            throw new Exception('Tenant class was not set');
        }

        return $this->belongsTo((string)$tenantClass);
    }

    public function setTypeAttribute(string $value = ''): void
    {
        $types = config('model-notes.note_types');

        if (! in_array($value, $types)) {
            throw new InvalidArgumentException(sprintf('Type of "%s" does not exist in "%s"', $value, implode(', ', $types)));
        }

        $this->attributes['type'] = strtolower($value);
    }

    public function setTenantIdAttribute(string $value = null): void
    {
        $resolver = config('model-notes.tenant_resolver');

        $this->attributes['tenant_id'] = $resolver && is_callable(app($resolver)) ? app($resolver)() : $value;
    }

    public function __toString(): string
    {
        return $this->note;
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }
}
