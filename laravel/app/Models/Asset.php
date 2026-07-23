<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Asset extends Model
{
    use HasUlids;
    protected $table = 'T20_assets';

    protected $primaryKey = 'T20_id';

    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'T20_datetime_created';
    const UPDATED_AT = 'T20_datetime_updated';

    protected $casts = [
        'T20_attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(
            AssetCategory::class,
            'T20T21_category_id',
            'T21_id',
            'T21_tag',
            'T21_brand',
            'T21_model',
            'T21_serial_number',
            'T21_attributes',
            'T21_status'
        );
    }
}
/*
//* Examples
$laptopCategory = AssetCategory::where('T21_name', 'Laptop')->first();

Asset::create([
    'T20_tag' => 'IT-LT-0001',
    'T20_brand' => 'Dell',
    'T20_model' => 'Latitude 7440',
    'T20_serialnumber' => 'DL123456',
    'T20T21_category_id' => $laptopCategory->T21_id,
    'T20_attributes' => [
        'cpu' => 'Intel i7',
        'ram' => '32GB',
        'storage' => '1TB SSD',
    ],
    'T20_status' => 'available',
]);
*/
