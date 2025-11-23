<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorAnalytic extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'device_type',
        'device_model',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'country',
        'city',
        'referrer',
        'page_url',
        'page_title',
        'session_id',
        'visit_duration',
        'is_bot',
        'bot_name',
    ];

    // Scopes for analytics queries
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeLastDays($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByBrowser($query, $browser)
    {
        return $query->where('browser', $browser);
    }

    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }
}
