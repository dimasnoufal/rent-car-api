<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Account;

class Booking extends Model
{

    protected $fillable = [
        'account_id',
        'car_id',
        'nama_mobil',
        'tahun_mobil',
        'alamat',
        'tanggal_pemesanan',
        'tanggal_pengembalian',
        'nama_pemesan',
        'no_hp',
        'foto_ktp',
        'foto_sim',
        'status',
        'layanan_supir',
        'total_harga',
    ];

    public function bookingtable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
