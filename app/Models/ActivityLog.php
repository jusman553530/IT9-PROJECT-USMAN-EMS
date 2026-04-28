<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'changes',
        'ip_address'
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to log activity
    public static function log($action, $module, $description, $changes = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}