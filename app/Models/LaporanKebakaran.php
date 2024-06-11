<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LaporanKebakaran extends Model
{
    use HasFactory;

    protected $table = 'laporan_kebakaran';

    protected $fillable = [
        'foto',
        'jenis',
        'nama',
        'telepon',
        'lokasi',
        'tanggal',
        'isi'
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
