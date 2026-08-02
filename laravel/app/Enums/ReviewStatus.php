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
            self::PENDING  => 'warning', // Yellow/Orange
            self::DECLINED => 'danger',  // Red
            self::ACCEPTED => 'success', // Green
        };
    }
}