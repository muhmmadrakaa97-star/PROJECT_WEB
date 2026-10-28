<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nomor_tiket
 * @property int $user_id
 * @property string $judul
 * @property string $deskripsi
 * @property string $kategori
 * @property string $lokasi
 * @property string|null $bukti
 * @property string $urgensi
 * @property bool $is_anonim
 * @property string $status
 * @property string|null $alasan_ditolak
 * @property string|null $unit_terkait
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 */
class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_tiket',
        'user_id',
        'judul',
        'deskripsi',
        'kategori',
        'lokasi',
        'bukti',
        'urgensi',
        'is_anonim',
        'status',
        'alasan_ditolak',
        'unit_terkait',
    ];

    protected $casts = [
        'is_anonim' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}