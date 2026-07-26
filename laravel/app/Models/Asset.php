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

    const CREATED_AT = 'T20_created_at';
    const UPDATED_AT = 'T20_updated_at';

    protected $casts = [
        'T20_attributes' => 'array',
    ];
    protected $fillable = [
        'T20_tag',
        'T20T21_category_id',
        'T20_brand',
        'T20_model',
        'T20_serial_number',
        'T20_specifications',
        'T20_status'
    ];

    public function category()
    {
        return $this->belongsTo(
            AssetCategory::class,
            'T20T21_category_id',
            'T21_id'
        );
    }
    public function getIdAttribute(){return $this->attributes['T20_id'];}
    public function getTagAttribute(){return $this->attributes['T20_tag'];}
    public function getBrandAttribute(){return $this->attributes['T20_brand'];}
    public function getModelAttribute(){return $this->attributes['T20_model'];}
    public function getSerialNumberAttribute(){return $this->attributes['T20_serial_number'];}
    public function getCategoryIdAttribute(){return $this->attributes['T20T21_category_id'];}
    public function getSpecificationsAttribute(){return $this->attributes['T20_specifications'];}
    public function getStatusAttribute(){return $this->attributes['T20_status'];}
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
