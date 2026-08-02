<?php

namespace App\Enums;

enum RequestStatus:string
{
    case PENDING =  'pending';
    case DECLINED = 'declined';
    case ACCEPTED = 'accepted';
    case PICKUP = 'pickup';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    public function label():string
    {
        return match($this){
            self::PENDING => 'Proses Semakan',
            self::DECLINED => 'Permohonan Ditolak',
            self::ACCEPTED => 'Permohonan Diterima',
            self::PICKUP => 'Sedia untuk Diambil',
            self::ACTIVE => 'Peminjaman',
            self::COMPLETED => 'Selesai'
        };
    }
    public function color(): string
    {
        return match($this) {
            self::PENDING   => 'warning', // Amber/Yellow
            self::DECLINED  => 'danger',  // Red
            self::ACCEPTED  => 'info',    // Blue
            self::PICKUP    => 'primary', // Indigo/Purple
            self::ACTIVE    => 'success', // Green
            self::COMPLETED => 'gray',    // Neutral
        };
    }
    public function isFinal(): bool
    {
        return match($this) {
            self::DECLINED, self::COMPLETED => true,
            default => false,
        };
    }
}
