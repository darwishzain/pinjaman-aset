<?php
//! T30_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\User;

class Request extends Model
{
    use HasUlids;
    protected $table = 'T30_requests';
    protected $primaryKey = 'T30_id';

    const CREATED_AT = 'T30_created_at';
    const UPDATED_AT = 'T30_updated_at';
    public const TYPE = [
        'individual',
        'department'
    ];
    protected $fillable = [
        'T30T10_user_id',
        'T30_reason',
        'T30_start_date',
        'T30_end_date',
        'T30_scheduled_pickup_at',
        'T30_location',
        'T30_remark',
        'T30_type',
        'T30T10_supported_by_id',
        'T30_supported_comment',
        'T30_supported_at',
        'T30T10_approved_by_id',
        'T30_approved_comment',
        'T30_approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'T30T10_user_id');
    }
    public function supportedBy()
    {
        return $this->belongsTo(User::class, 'T30T10_supported_by_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'T30T10_approved_by_id');
    }
}
