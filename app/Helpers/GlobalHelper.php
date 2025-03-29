<?php
declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;

/**
 * Get a list of countries.
 *
 * @return array<object> Array of objects with 'id' and 'name' properties
 */
if (!function_exists('getCountries')) {
    function getCountries(): array
    {
        return [
            (object) ['id' => 'bd', 'name' => 'Bangladesh'],
            (object) ['id' => 'bt', 'name' => 'Bhutan'],
        ];
    }
}

/**
 * Get the authenticated user.
 *
 * @return \Illuminate\Auth\Authenticatable|null The authenticated user or null
 */
if (!function_exists('AuthUser')) {
    function AuthUser(): ?Authenticatable
    {
        return \Auth::user();
    }
}

/**
 * Get a list of time zones.
 *
 * @return array<object> Array of objects with 'id' and 'name' properties
 */
if (!function_exists('getTimeZones')) {
    function getTimeZones(): array
    {
        return [
            (object) ['id' => 'asia/dhaka', 'name' => 'Asia/Dhaka'],
            (object) ['id' => 'asia/tokyo', 'name' => 'Asia/Tokyo'],
        ];
    }
}

/**
 * Get a list of time formats.
 *
 * @return array<object> Array of objects with 'id' and 'name' properties
 */
if (!function_exists('getTimeFormats')) {
    function getTimeFormats(): array
    {
        return [
            (object) ['id' => 'H:i:s', 'name' => 'H:i:s'],
            (object) ['id' => 'H:i', 'name' => 'H:i'],
            (object) ['id' => 'h:i:s a', 'name' => 'h:i:s A'],
            (object) ['id' => 'h:i a', 'name' => 'h:i A'],
        ];
    }
}

/**
 * Get a list of date formats.
 *
 * @return array<object> Array of objects with 'id' and 'name' properties
 */
if (!function_exists('getDateFormats')) {
    function getDateFormats(): array
    {
        return [
            (object) ['id' => 'Y-m-d', 'name' => 'Y-m-d'],
            (object) ['id' => 'd/m/Y', 'name' => 'd/m/Y'],
            (object) ['id' => 'm/d/Y', 'name' => 'm/d/Y'],
            (object) ['id' => 'F j, Y', 'name' => 'F j, Y'],
            (object) ['id' => 'D, M j, Y', 'name' => 'D, M j, Y'],
            (object) ['id' => 'l, F j, Y', 'name' => 'l, F j, Y'],
        ];
    }
}

/**
 * Format a date string.
 *
 * @param string $date The date string to format
 * @return string The formatted date string (e.g., '10 October 2023')
 */
if (!function_exists('DateFormate')) {
    function DateFormate(string $date): string
    {
        $carbonDate = Carbon::parse($date);
        return $carbonDate->format('j F Y');
    }
}

/**
 * Calculate the number of years between two dates.
 *
 * @param string|Carbon $startDate The start date
 * @param string|Carbon $endDate The end date
 * @return string The duration in years (e.g., "3 years")
 */
if (!function_exists('calculateYearDuration')) {
    function calculateYearDuration($startDate, $endDate): string
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
    
        $years = $start->diffInYears($end);
        
        return "{$years} years";
    }
}
