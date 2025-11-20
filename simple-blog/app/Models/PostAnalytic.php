<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAnalytic extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'referrer_url',
        'referrer_type',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'country',
        'country_code',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Categorize referrer URL into type
     */
    public static function categorizeReferrer($referrer)
    {
        if (empty($referrer)) {
            return 'direct';
        }

        $referrer = strtolower($referrer);
        $currentDomain = parse_url(config('app.url'), PHP_URL_HOST);

        // Check if internal
        if (strpos($referrer, $currentDomain) !== false) {
            return 'internal';
        }

        // Check if search engine
        $searchEngines = ['google', 'bing', 'yahoo', 'duckduckgo', 'baidu', 'yandex'];
        foreach ($searchEngines as $engine) {
            if (strpos($referrer, $engine) !== false) {
                return 'search';
            }
        }

        // Check if social media
        $socialMedia = ['facebook', 'twitter', 'instagram', 'linkedin', 'pinterest', 'reddit', 'tiktok', 'youtube'];
        foreach ($socialMedia as $social) {
            if (strpos($referrer, $social) !== false) {
                return 'social';
            }
        }

        // Otherwise it's a referral
        return 'referral';
    }

    /**
     * Parse user agent to detect device type
     */
    public static function parseDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);

        // Check for mobile
        if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $userAgent)) {
            // Check if tablet
            if (preg_match('/(ipad|tablet|playbook|silk)/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Parse user agent to detect browser
     */
    public static function parseBrowser($userAgent)
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }

        $browsers = [
            'Edge' => 'Edg',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Opera' => 'OPR|Opera',
            'IE' => 'MSIE|Trident',
        ];

        foreach ($browsers as $browser => $pattern) {
            if (preg_match("/$pattern/i", $userAgent)) {
                return $browser;
            }
        }

        return 'Other';
    }

    /**
     * Get country from IP address using free API
     */
    public static function getCountryFromIP($ip)
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return ['country' => 'Local', 'country_code' => 'LC'];
        }

        try {
            // Using ip-api.com free service (no API key required, 45 requests/minute limit)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,countryCode");
            
            if ($response) {
                $data = json_decode($response, true);
                if ($data && isset($data['country'])) {
                    return [
                        'country' => $data['country'],
                        'country_code' => $data['countryCode'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return ['country' => 'Unknown', 'country_code' => null];
    }
}

