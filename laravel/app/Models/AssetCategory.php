<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AssetCategory extends Model
{
    use HasUlids;
    protected $table = 'T21_asset_categories';

    protected $primaryKey = 'T21_id';

    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'T21_created_at';
    const UPDATED_AT = 'T21_updated_at';

    protected $fillable = [
        'T21_name',
    ];
    public function assets()
    {
        return $this->hasMany(Asset::class, 'T20T21_category_id', 'T21_id');
    }
}
