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

    protected $appends = [
        'formatted_note',
        'time_ago',
    ];

    protected $casts = [
        'note' => EncryptNoteCast::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Note $model) {
            $resolver = config('model-notes.tenant_resolver');

            if ($resolver) {
                $model->attributes['tenant_id'] = $resolver && is_callable(app($resolver)) ? app($resolver)() : null;
            }
        });
    }

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
        $types = array_keys(config('model-notes.note_types'));

        if (! in_array($value, $types)) {
            throw new InvalidArgumentException(sprintf('Type of "%s" does not exist in "%s"', $value, implode(', ', $types)));
        }

        $this->attributes['type'] = strtolower($value);
    }

    public function getFormattedNoteAttribute()
    {
        $type = config('model-notes.note_types.' . $this->type);

        if (! $type) {
            return $this->attributes['note'];
        }

        if (is_array($type)) {
            // call the method on the class with the note field as the first argument
            return app($type[0])->{$type[1]}($this->attributes['note']);
        }

        if (class_exists($type)) {
            // call __invoke on the class
            return app($type)($this->attributes['note']);
        }

        if (function_exists($type)) {
            return call_user_func($type, $this->attributes['note']);
        }

        throw new Exception(sprintf('Could not find a way to render note of type "%s"', $type));
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }

    public function __toString(): string
    {
        return $this->note;
    }
}
