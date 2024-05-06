<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ItemProgress extends Model
{
    use HasFactory, HasCompositeKey, LogsActivity;

    protected $table = 'item_progress';

    //Composite primary keys
    protected $primaryKey = ['UserID', 'CompanyID', 'ModuleID', 'ItemID'];

    protected $fillable = [ 
        'UserID',
        'CompanyID',
        'ModuleID',
        'ItemID',
        'IsCompleted',
    ];

    //Relationship with Assigned Module model
    public function assignedModule() : BelongsTo
    {
        return $this->belongsTo(AssignedModule::class, ['UserID', 'CompanyID', 'ModuleID'], ['UserID', 'CompanyID', 'ModuleID']);
    }

    //Relationship with Item model
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'ItemID', 'id');
    }

    //Logging Model Changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['UserID', 'CompanyID', 'ModuleID', 'ItemID', 'IsCompleted']);
    }

}
