<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key');
    }

    /**
     * Search for a place by text query near Setagaya.
     * Returns ['place_id', 'lat', 'lng', 'address'] or null.
     */
    public function findPlace(string $query, ?float $lat = 35.6340, ?float $lng = 139.6480): ?array
    {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_KEY_HERE') {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', [
                'input' => $query,
                'inputtype' => 'textquery',
                'fields' => 'place_id,geometry,formatted_address,name',
                'locationbias' => "circle:5000@{$lat},{$lng}",
                'language' => 'ja',
                'key' => $this->apiKey,
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'OK' && !empty($data['candidates'])) {
                $candidate = $data['candidates'][0];
                $location = $candidate['geometry']['location'] ?? null;

                if ($location) {
                    return [
                        'place_id' => $candidate['place_id'] ?? null,
                        'lat' => $location['lat'],
                        'lng' => $location['lng'],
                        'address' => $candidate['formatted_address'] ?? null,
                        'name' => $candidate['name'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('GooglePlaces findPlace failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Geocode an address string to coordinates.
     * Returns ['lat', 'lng', 'address'] or null.
     */
    public function geocode(string $address): ?array
    {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_KEY_HERE') {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'language' => 'ja',
                'region' => 'jp',
                'key' => $this->apiKey,
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'OK' && !empty($data['results'])) {
                $result = $data['results'][0];
                $location = $result['geometry']['location'] ?? null;

                if ($location) {
                    return [
                        'lat' => $location['lat'],
                        'lng' => $location['lng'],
                        'address' => $result['formatted_address'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('GooglePlaces geocode failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Try Places API first, then Geocoding, then return fallback coords.
     */
    public function resolveLocation(string $name, ?string $address = null, ?float $fallbackLat = null, ?float $fallbackLng = null): array
    {
        // 1. Try Place search by name
        $result = $this->findPlace($name);
        if ($result) {
            Log::info("Places API hit for '{$name}': place_id={$result['place_id']}");
            return $result;
        }

        // 2. If address provided, try geocoding
        if ($address) {
            $geo = $this->geocode($address);
            if ($geo) {
                Log::info("Geocoding hit for '{$address}'");
                return array_merge($geo, ['place_id' => null, 'name' => $name]);
            }
        }

        // 3. Fallback to manual coordinates
        Log::info("Using fallback coordinates for '{$name}'");
        return [
            'place_id' => null,
            'lat' => $fallbackLat,
            'lng' => $fallbackLng,
            'address' => $address,
            'name' => $name,
        ];
    }
}
