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
        return $this->hasMany(Asset::class, 'T20_assets', 'T20_id','T20_name');
    }
    public function getIdAttribute(){return $this->attributes['T21_id'];}
    public function getNameAttribute(){return $this->attributes['T21_name'];}
    public function getCreatedAtAttribute(){return $this->attributes['T20_created_at'];}
    public function getUpdatedAtAttribute(){return $this->attributes['T20_updated_at'];}
}
