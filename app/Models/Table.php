<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $fillable = ['version_id','name','column_names','data_file'];

    public function version(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function columns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function generateSchemaString() : string {
        $this->loadMissing('columns');
        return $this
                    ->columns
                    ->map(static fn (Column $column) => $column->schema_string)
                    ->prepend('version_id:unsignedBigInteger:foreign')
                    ->implode(',');
    }
}
