<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatementLetter extends Model
{
    protected $fillable = [
        'violation_id', 'generated_pdf', 'scanned_signed_file', 'extracted_text',
    ];

    public function violation(): BelongsTo
    {
        return $this->belongsTo(Violation::class);
    }
}
