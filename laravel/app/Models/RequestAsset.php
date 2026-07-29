<?php
//!! T31_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AssetCategory;

class RequestAsset extends Model
{
    protected $table = 'T31_request_asset';
    protected $primaryKey = 'T31_id';

    protected $fillable = [
        'T31T30_request_id',
        'T31T21_asset_category_id',
        'T31_quantity'
    ];
    public function assetCategory()
    {
        return $this->belongsTo(assetCategory::class,'T31T21_asset_category_id');
    }
}
