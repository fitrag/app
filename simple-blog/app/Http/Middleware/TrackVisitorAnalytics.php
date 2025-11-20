<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorAnalytic;
use Jenssegers\Agent\Agent;

class TrackVisitorAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests on public pages (not admin, api, or assets)
        if ($request->isMethod('GET') && 
            !$request->is('admin/*') && 
            !$request->is('api/*') && 
            !$request->is('login') &&
            !$request->is('register') &&
            !str_contains($request->path(), '.')) {
            
            try {
                $agent = new Agent();
                $agent->setUserAgent($request->userAgent());
                
                // Get or create session ID
                $sessionId = session()->getId();
                if (!$sessionId) {
                    session()->start();
                    $sessionId = session()->getId();
                }
                
                // Determine device type
                $deviceType = 'desktop';
                if ($agent->isMobile()) {
                    $deviceType = 'mobile';
                } elseif ($agent->isTablet()) {
                    $deviceType = 'tablet';
                }
                
                // Get geolocation data from IP
                $country = null;
                $city = null;
                $ipAddress = $request->ip();
                
                // Only fetch geolocation for non-local IPs
                if ($ipAddress !== '127.0.0.1' && $ipAddress !== '::1' && !str_starts_with($ipAddress, '192.168.')) {
                    try {
                        $geoData = $this->getGeolocation($ipAddress);
                        $country = $geoData['country'] ?? null;
                        $city = $geoData['city'] ?? null;
                    } catch (\Exception $e) {
                        // Silently fail geolocation lookup
                        \Log::debug('Geolocation lookup failed: ' . $e->getMessage());
                    }
                }
                
                // Collect analytics data
                VisitorAnalytic::create([
                    'ip_address' => $ipAddress,
                    'user_agent' => $request->userAgent(),
                    'device_type' => $deviceType,
                    'device_model' => $agent->device() ?: null,
                    'browser' => $agent->browser(),
                    'browser_version' => $agent->version($agent->browser()),
                    'platform' => $agent->platform(),
                    'platform_version' => $agent->version($agent->platform()),
                    'country' => $country,
                    'city' => $city,
                    'referrer' => $request->header('referer'),
                    'page_url' => $request->fullUrl(),
                    'page_title' => null, // Will be updated via JavaScript if needed
                    'session_id' => $sessionId,
                    'visit_duration' => 0,
                ]);
            } catch (\Exception $e) {
                // Silently fail to not break the application
                \Log::error('Visitor analytics tracking failed: ' . $e->getMessage());
            }
        }
        
        return $next($request);
    }
    
    /**
     * Get geolocation data from IP address using ip-api.com
     *
     * @param string $ipAddress
     * @return array
     */
    private function getGeolocation(string $ipAddress): array
    {
        try {
            // Use ip-api.com free API (no key required, 45 requests/minute limit)
            $url = "http://ip-api.com/json/{$ipAddress}?fields=status,country,city";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2); // 2 second timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'city' => $data['city'] ?? null,
                    ];
                }
            }
            
            return ['country' => null, 'city' => null];
        } catch (\Exception $e) {
            \Log::debug('Geolocation API error: ' . $e->getMessage());
            return ['country' => null, 'city' => null];
        }
    }
}
