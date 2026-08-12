<?php
//! T20_*
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Enums\AssetStatus;
use App\Models\Transaction;

class Asset extends Model
{
    use HasUlids;
    protected $table = 'T20_assets';
    protected $primaryKey = 'T20_id';

    const CREATED_AT = 'T20_created_at';
    const UPDATED_AT = 'T20_updated_at';

    protected $casts = [
        'T20_specifications' => 'array',
        'T20_status' => AssetStatus::class,
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
    public const CONNECTORS = [
        'hdmi'         => 'HDMI',
        'vga'          => 'VGA',
        'rj45'         => 'RJ45 (Ethernet)',
        'display_port' => 'DisplayPort',
        'usb_a'        => 'USB-A',
        'usb_c'        => 'USB-C',
    ];
    public const STATUS = [
        'pending' => "Processing",
        'active' => "Already Lended",
        'available' => 'Available for Lending',
        'maintenance' => 'Under Maintenance',
        'lost' => 'Mising',
        'retired' => 'To be Disposed',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class,'T20T21_category_id');
    }
    public function getConnectorLabels(): array
    {
        return self::CONNECTORS;
    }

    public function getConnectorLabel(string $key, string $default = ''): string
    {
        return self::CONNECTORS[$key] ?? $default;
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'T40T20_asset_id', 'T20_id');
    }
    public function getIdAttribute(){return $this->attributes['T20_id'] ?? null;}
    public function getTagAttribute(){return $this->attributes['T20_tag'] ?? null;}
    public function getCategoryIdAttribute(){return $this->attributes['T20T21_category_id'] ?? null;}
    public function getBrandAttribute(){return $this->attributes['T20_brand'] ?? null;}
    public function getModelAttribute(){return $this->attributes['T20_model'] ?? null;}
    public function getSerialNumberAttribute(){return $this->attributes['T20_serial_number'] ?? null;}
    public function getSpecificationsAttribute(){return $this->attributes['T20_specifications'] ?? null;}
    public function getStatusAttribute(){return $this->attributes['T20_status'] ?? null;}

    public function setTagAttribute($value){$this->attributes['T20_tag'] = $value;}
    public function setCategoryIdAttribute($value){$this->attributes['T20T21_category_id'] = $value;}
    public function setBrandAttribute($value){$this->attributes['T20_brand'] = $value;}
    public function setModelgAttribute($value){$this->attributes['T20_model'] = $value;}
    public function setSerialNumberAttribute($value){$this->attributes['T20_serial_number'] = $value;}
    public function setSpecificationsAttribute($value){$this->attributes['T20_specifications'] = $value;}
    public function setStatusAttribute($value){$this->attributes['T20_status'] = $value;}
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
