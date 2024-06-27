<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LaporanMedis extends Model
{
    use HasFactory;
    protected $table = 'laporan_medis';

    protected $fillable = [
        'foto',
        'jenis',
        'nama',
        'telepon',
        'lokasi',
        'tanggal',
        'isi'
    ];

    protected $casts = [
        'tanggal' => 'string',
    ];

    /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/public/' . $image),
        );
    }
}
