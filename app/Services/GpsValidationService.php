<?php

namespace App\Services;

class GpsValidationService
{
    /**
     * Radius bumi dalam meter.
     */
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Hitung jarak antara dua titik koordinat menggunakan rumus Haversine.
     * Return nilai dalam meter.
     *
     * @param float $lat1  Latitude titik 1 (user)
     * @param float $lng1  Longitude titik 1 (user)
     * @param float $lat2  Latitude titik 2 (placement)
     * @param float $lng2  Longitude titik 2 (placement)
     * @return float Jarak dalam meter
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_METERS * $c;
    }

    /**
     * Cek apakah user berada dalam radius placement.
     *
     * @param float $userLat     Latitude user saat ini
     * @param float $userLng     Longitude user saat ini
     * @param float $placeLat    Latitude placement
     * @param float $placeLng    Longitude placement
     * @param int   $radiusMeters Radius yang diizinkan (meter)
     * @return bool
     */
    public function isWithinRadius(
        float $userLat,
        float $userLng,
        float $placeLat,
        float $placeLng,
        int $radiusMeters
    ): bool {
        $distance = $this->calculateDistance($userLat, $userLng, $placeLat, $placeLng);
        return $distance <= $radiusMeters;
    }

    /**
     * Parse "latitude,longitude" string dari tabel placements.coordinate.
     *
     * @param string $coordinate Format: "-7.716667,109.000000"
     * @return array{lat: float, lng: float}|null
     */
    public function parseCoordinate(string $coordinate): ?array
    {
        $parts = explode(',', trim($coordinate));

        if (count($parts) !== 2) {
            return null;
        }

        $lat = (float) trim($parts[0]);
        $lng = (float) trim($parts[1]);

        // Basic sanity check
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return null;
        }

        return ['lat' => $lat, 'lng' => $lng];
    }

    /**
     * Full validation: parse placement coordinate, hitung jarak, dan cek radius.
     * Return array hasil lengkap untuk dipakai di Livewire component.
     *
     * @param float  $userLat         Latitude user
     * @param float  $userLng         Longitude user
     * @param string $placementCoord  Format "lat,lng" dari placement.coordinate
     * @param int    $radiusMeters    Radius dari placement.radius
     * @return array{valid: bool, distance: float, radius: int, message: string}
     */
    public function validate(
        float $userLat,
        float $userLng,
        string $placementCoord,
        int $radiusMeters
    ): array {
        $coords = $this->parseCoordinate($placementCoord);

        if (!$coords) {
            return [
                'valid'    => false,
                'distance' => 0,
                'radius'   => $radiusMeters,
                'message'  => 'Koordinat lokasi kerja tidak valid. Hubungi admin.',
            ];
        }

        $distance = $this->calculateDistance($userLat, $userLng, $coords['lat'], $coords['lng']);
        $isValid  = $distance <= $radiusMeters;

        return [
            'valid'    => $isValid,
            'distance' => round($distance),
            'radius'   => $radiusMeters,
            'message'  => $isValid
                ? 'Anda berada dalam area kerja. Absensi diizinkan.'
                : sprintf(
                    'Anda berada %.0f meter dari lokasi kerja. Radius maksimum %d meter.',
                    $distance,
                    $radiusMeters
                ),
        ];
    }
}
