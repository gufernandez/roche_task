<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = "records";

    protected $fillable = [
        'status_type',
        'date_created',
        'compliance_due_date',
        'original_date_closed',
        'discrepancy_completed_date',
        'discrepancy_type',
        'record_type'
    ];
}
