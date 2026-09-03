<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldOfStudy extends Model
{
    // Gunakan nama tabel default Laravel 'field_of_studies'
    protected $table = 'field_of_studies';

    protected $fillable = ['name', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FieldOfStudy::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(FieldOfStudy::class, 'parent_id');
    }

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class, 'field_of_study_id');
    }

    public function ancestorsPath(): array
    {
        $path = [$this];
        $node = $this;
        while ($node->parent) {
            $node = $node->parent;
            array_unshift($path, $node);
        }
        return $path;
    }

    public function rumpunName(): string
    {
        return $this->ancestorsPath()[0]->name;
    }

    public function subIlmuName(): string
    {
        $path = $this->ancestorsPath();
        return $path[1]->name ?? $path[0]->name;
    }
}