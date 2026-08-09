<?php
namespace App\Enums;
enum AssetStatus:string
{
    case AVAILABLE =  'available';
    case ACTIVE = 'active';
    case MAINTENANCE = 'maintenance';

    public function label():string
    {
        return match($this){
            self::AVAILABLE => 'Dalam Simpanan',
            self::ACTIVE => 'Sedang Dipinjam',
            self::MAINTENANCE => 'Penyelenggaraan',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::AVAILABLE   => 'bg-green-100 text-green-800', // Warning
            self::ACTIVE  => 'bg-blue-100 text-blue-800',    // Info
            self::MAINTENANCE  => 'bg-red-100 text-red-800',      // Danger
        };
    }
}