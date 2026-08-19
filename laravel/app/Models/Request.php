<?php
//! T30_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\User;
use App\Models\AssetCategory;
use App\Models\RequestAsset;
use App\Models\Transaction;
use App\Enums\RequestStatus;
use App\Enums\ReviewStatus;

class Request extends Model
{
    use HasUlids;
    protected $table = 'T30_requests';
    protected $primaryKey = 'T30_id';

    const CREATED_AT = 'T30_created_at';
    const UPDATED_AT = 'T30_updated_at';
    public const TYPE = [
        'individual'=>'Individu',
        'department'=>'Jabatan/Bahagian'
    ];
    protected function casts(): array
    {
        return [
            'T30_status'   => RequestStatus::class,
            'T30_support_status' => ReviewStatus::class,
            'T30_approve_status'  => ReviewStatus::class,
            'T30_support_at'     => 'datetime',
            'T30_approve_at'      => 'datetime',
        ];
    }
    protected $fillable = [
        'T30T10_user_id',
        'T30_reason',
        'T30_start_date',
        'T30_end_date',
        'T30_scheduled_pickup_at',
        'T30_location',
        'T30_remark',
        'T30_type',
        'T30T10_support_by_id',
        'T30_support_comment',
        'T30_support_status',
        'T30_support_at',
        'T30T10_approve_by_id',
        'T30_approve_comment',
        'T30_approve_status',
        'T30_approve_at',
        'T30_status'
    ];
    public function needSupport()
    {
        return $this->T30_support_status === ReviewStatus::PENDING
        && $this->T30_approve_status === ReviewStatus::PENDING
        && $this->T30_status === RequestStatus::PENDING;
    }
    public function isSupported()
    {
        return $this->T30_support_status === ReviewStatus::ACCEPTED;
    }
    public function isNotSupported()
    {
        return $this->T30_support_status === ReviewStatus::REJECTED;
    }
    public function needApprove()
    {
        return $this->T30_support_status === ReviewStatus::ACCEPTED
        && $this->T30_approve_status === ReviewStatus::PENDING
        && $this->T30_status === RequestStatus::PENDING;
    }
    public function isApproved()
    {
        return $this->T30_approve_status === ReviewStatus::ACCEPTED;
    }
    public function isRejected()
    {
        return $this->T30_approve_status === ReviewStatus::REJECTED;
    }
    public function isPending()
    {
        return $this->T30_status === RequestStatus::PENDING;
    }
    public function isPickup()
    {
        return $this->T30_status === RequestStatus::PICKUP;
    }
    public function isActive()
    {
        return $this->T30_status === RequestStatus::ACTIVE;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'T30T10_user_id');
    }
    public function supportBy()
    {
        return $this->belongsTo(User::class, 'T30T10_support_by_id');
    }
    public function approveBy()
    {
        return $this->belongsTo(User::class, 'T30T10_approve_by_id');
    }
    public function requestAssets()
    {
        return $this->hasMany(RequestAsset::class, 'T31T30_request_id', 'T30_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'T40T30_request_id', 'T30_id');
    }
    public function transactionsIn()
    {
        return $this->hasMany(Transaction::class, 'T40T30_request_id','T30_id')
            ->where('T40_action', 'in');
    }
    public function transactionsOut()
    {
        return $this->hasMany(Transaction::class, 'T40T30_request_id','T30_id')
            ->where('T40_action', 'out');
    }
    public function canUpdate():bool
    {
        return $this->T30_status == RequestStatus::PENDING
        && $this->T30_support_status == ReviewStatus::PENDING
        && $this->T30_approve_status == ReviewStatus::PENDING
        && $this->T30T10_user_id === auth()->user()->id;
    }
    public function canSupport():bool
    {
        $authuser = auth()->user();
        return $this->needSupport()
        && $this->user?->group == $authuser?->group
        && $authuser?->can('support:requests');
    }
    public function canApprove():bool
    {
        $authuser = auth()->user();
        return $this->needApprove()
        && $authuser?->can('approve:requests');
    }
    public function transactionCompleted():bool
    {
        $transactionoutCount = Transaction::where('T40T30_request_id',$this->T30_id)
            ->where('T40_action','out')
            ->count();
        $transactioninCount = Transaction::where('T40T30_request_id',$this->T30_id)
            ->where('T40_action','in')
            ->count();
        return $transactionoutCount > 0 && $transactionoutCount === $transactioninCount;
    }
}
