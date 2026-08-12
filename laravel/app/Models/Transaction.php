<?php
//!! T40_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\User;
use App\Models\Asset;
use App\Models\Request;

class Transaction extends Model
{
    use HasUlids;
    protected $table = 'T40_transactions';
    protected $primaryKey = 'T40_id';

    const CREATED_AT = 'T40_created_at';
    const UPDATED_AT = 'T40_updated_at';

    protected $fillable = [
        'T40T30_request_id',
        'T40T20_asset_id',
        'T40_action',
        'T40T10_giver_id',
        'T40T10_taker_id',
        'T40T10_handler_id'
    ];
    public CONST ACTIONS = [
        'out' => 'Transaksi Keluar',
        'in' => 'Transaksi Masuk',
    ];
    public function getActionLabels(): array
    {
        return self::ACTIONS;
    }

    public function getActionLabel(string $key, string $default = ''): string
    {
        return self::ACTIONS[$key] ?? $default;
    }
    public function getTransactionInAttribute()
    {
        return self::where('T40T30_request_id', $this->T40T30_request_id)
            ->where('T40T20_asset_id', $this->T40T20_asset_id)
            ->where('T40_action', 'in')
            ->first();
    }
    public function hasTransactionIn(): bool
    {
        return $this->transactionIn !== null;
    }
    public function request()
    {
        return $this->belongsTo(Request::class,'T40T30_request_id');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class,'T40T20_asset_id');
    }
    public function giver()
    {
        return $this->belongsTo(User::class,'T40T10_giver_id');
    }
    public function taker()
    {
        return $this->belongsTo(User::class,'T40T10_taker_id');
    }
    public function handler()
    {
        return $this->belongsTo(User::class,'T40T10_handler_id');
    }
}
