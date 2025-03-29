<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait GeoLocationTrait
{
    public $location;

    public const CACHE_PREFIX = 'geo_location_';

    public function fetchLocation(): ?object
    {
        $ip = request()->ip();
        $cacheKey = self::CACHE_PREFIX . $ip;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $url = "http://ip-api.com/json/$ip";
            $this->location = json_decode(file_get_contents($url));

            Cache::put($cacheKey, $this->location, 60 * 24);
        } catch (Exception $e) {
            $this->location = null;
            Log::error('GeoLocation fetching error: ' . $e->getMessage());
        }

        return $this->location;
    }

    public function getCountryName(): ?string
    {
        return $this->location->country ?? null;
    }

    public function getCountryCode(): ?string
    {
        return $this->location->countryCode ?? null;
    }

    public function getRegionName(): ?string
    {
        return $this->location->regionName ?? null;
    }

    public function getCity(): ?string
    {
        return $this->location->city ?? null;
    }

    public function getZipCode(): ?string
    {
        return $this->location->zip ?? null;
    }

    public function getLatitude(): ?float
    {
        return $this->location->lat ?? null;
    }

    public function getLongitude(): ?float
    {
        return $this->location->lon ?? null;
    }

    public function getTimezone(): ?string
    {
        return $this->location->timezone ?? null;
    }

    public function getIsp(): ?string
    {
        return $this->location->isp ?? null;
    }

    public function getOrg(): ?string
    {
        return $this->location->org ?? null;
    }

    public function getAs(): ?string
    {
        return $this->location->as ?? null;
    }

    public function getQuery(): ?string
    {
        return $this->location->query ?? null;
    }
}
