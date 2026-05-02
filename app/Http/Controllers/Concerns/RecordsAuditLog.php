<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait RecordsAuditLog
{
    protected function audit(string $action, Model $subject, string $description, array $properties = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject::class,
            'subject_id' => $subject->getKey(),
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
