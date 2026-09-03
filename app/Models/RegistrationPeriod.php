<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RegistrationPeriod extends Model
{
    use HasFactory;

    const STATUS_BELUM_DIBUKA = 'belum_dibuka';
    const STATUS_DIBUKA       = 'dibuka';
    const STATUS_DITUTUP      = 'ditutup';

    protected $fillable = [
        'academic_year',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_DIBUKA;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_DITUTUP;
    }

    public function isNotOpenedYet(): bool
    {
        return $this->status === self::STATUS_BELUM_DIBUKA;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DIBUKA  => 'Dibuka',
            self::STATUS_DITUTUP => 'Ditutup',
            default               => 'Belum Dibuka',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DIBUKA  => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
            self::STATUS_DITUTUP => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
            default               => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
        };
    }

    /**
     * Tutup otomatis semua periode yang end_date-nya sudah lewat.
     */
    public static function refreshStatuses(): void
    {
        static::where('status', '!=', self::STATUS_DITUTUP)
            ->whereDate('end_date', '<', Carbon::today())
            ->update(['status' => self::STATUS_DITUTUP]);
    }

    /**
     * Periode yang sedang relevan (terbaru berdasarkan tanggal mulai),
     * setelah status otomatis di-refresh.
     */
    public static function current(): ?self
    {
        static::refreshStatuses();

        return static::orderByDesc('start_date')->orderByDesc('id')->first();
    }
}