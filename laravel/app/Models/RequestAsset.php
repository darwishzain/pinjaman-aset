<?php
//!! T31_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AssetCategory;
use App\Models\Request;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class RequestAsset extends Model
{
    use HasUlids;
    protected $table = 'T31_request_asset';
    protected $primaryKey = 'T31_id';

    public $timestamps = false;
    protected $fillable = [
        'T31T30_request_id',
        'T31T21_asset_category_id',
        'T31_quantity'
    ];
    public function request()
    {
        return $this->belongsTo(Request::class, 'T31T30_request_id');
    }
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'T31T21_asset_category_id');
    }
    public function canGive():bool
    {
        $category_id = $this->category->T21_id;
        $transactionsout = Transaction::where('T40T30_request_id',$this->T31T30_request_id)
            ->where('T40_action','out')
            ->whereHas('asset', function ($query) use ($category_id) {
                $query->where('T20T21_category_id', $category_id);
            });
        return (int)$this->T31_quantity > $transactionsout->count();
    }
    public function transactionCategoryCount($category_id)
    {
        return $this->request->transactions->whereIn('asset.T20T21_category_id',$category_id)->count();
        return $this->T31_quantity;
        //return $this->request->transactions->asset->category;
    }
    public function isRequestAssetFulfilled($category_id)
    {
        return $this->request->transactions
            ->whereIn('asset.T20T21_category_id',$category_id)
            ->count() >= $this->T31_quantity;
    }
}
