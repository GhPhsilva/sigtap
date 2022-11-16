<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $table = 'columns';

    protected $fillable = ['version_id','table_id','name','import_length','import_start','import_end','type'];

    public function table(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function getSchemaStringAttribute(): string
    {
        return $this->name.':'.$this->getColumnType($this->type).':nullable';
    }

    private function getColumnType($value): string
    {

        return match ($value) {
            'NUMBER' => 'integer',
            default => 'string',
        };
    }
}
