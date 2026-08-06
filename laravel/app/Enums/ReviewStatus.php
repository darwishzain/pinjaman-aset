<?php
namespace App\Enums;
enum ReviewStatus:string
{
    case PENDING =  'pending';
    case DECLINED = 'declined';
    case ACCEPTED = 'accepted';

    public function label():string
    {
        return match($this){
            self::PENDING => 'Proses Semakan',
            self::DECLINED => 'Permohonan Ditolak',
            self::ACCEPTED => 'Permohonan Diterima',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::PENDING   => 'bg-yellow-100 text-yellow-800', // Warning
            self::DECLINED  => 'bg-red-100 text-red-800',      // Danger
            self::ACCEPTED  => 'bg-blue-100 text-blue-800',    // Info
        };
    }
}