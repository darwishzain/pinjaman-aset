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
            self::PENDING   => 'bg-yellow-100 text-yellow-800', // Warning
            self::DECLINED  => 'bg-red-100 text-red-800',      // Danger
            self::ACCEPTED  => 'bg-blue-100 text-blue-800',    // Info
            self::PICKUP    => 'bg-indigo-100 text-indigo-800',// Primary
            self::ACTIVE    => 'bg-green-100 text-green-800',  // Success
            self::COMPLETED => 'bg-gray-100 text-gray-800',    // Gray
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
