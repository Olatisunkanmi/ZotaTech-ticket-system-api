<?php

namespace App\Helper;

use Illuminate\Support\Str;
use App\Models\{Event, Url, Category};
use Illuminate\Support\Facades\{Http, Cache};


class Helper
{

    public static function getDomain(): string
    {
        $domain = $_SERVER['HTTP_HOST'];
        return $domain;
    }

    public static function generateLink(string $title): array
    {
        $ulid = Str::ulid();
        $array = explode(' ', $title);
        $text = implode('-', $array);

        $domain = Helper::getDomain();

        $long_url = 'https://' . $domain . '/api/v1/events/' . $text . '-tickets-' . $ulid;
        $short_url = 'https://' . $domain . '/api/v1/e/' . $ulid;


        return [
            'long_url' => $long_url,
            'short_id' => $ulid,
            'short_url' => $short_url,
        ];
    }

    public static function getRandomImage(): ?string
    {
        $response = Http::get('https://robohash.org/' . Str::random(10));

        if ($response->ok()) {
            $contentType = $response->headers()['Content-Type'][0];
            $imageContent = $response->body();

            if (strpos($contentType, 'image') !== false) {
                $imageContent = mb_convert_encoding($imageContent, 'UTF-8', 'UTF-8');
                return $imageContent;
            }
        }

        return null;  // Return null or handle the error case as needed
    }

    /**
     * Save data to cache
     * @param string $key - key of the data
     * @param string $value - value of the data
     * @param int $time - time of the data
     */

    public static function saveToCache(string $key, mixed $value, $time): string
    {
        return Cache::remember($key, $time, function () use ($value) {
            return $value;
        });
    }

    /**
     * Get data from cache
     * @param string $key - key of the data
     * @param string $id - id of the data
     */

    public static function getFromCache($key, $id): mixed
    {
        return Cache::get($key . $id);
    }

    /**
     * Delete data from cache
     * @param string $key - key of the data
     * @param string $id - id of the data
     */
    public static function deleteFromCache($key, $id): bool
    {
        return Cache::forget($key . $id);
    }

    /**
     * Delete all data from cache
     * @param string $key - key of the data
     */

    public static function deleteAllFromCache($key): bool
    {
        return Cache::forget($key);
    }


    /**
     * Update data in cache
     * @param string $key - key of the data
     * @param string $id - id of the data
     * @param string $value - value of the data
     * @param string $time - time of the data
     */

    public static function updateCache($key, $id, $value, $time): mixed
    {
        return  Cache([$key . $id => $value], $time);
    }

    /**
     * Update Event clicks in DB
     */
    public static function updateEventClicks(object $event): void
    {
        $url = $event->url;
        $url->clicks = $url->clicks + 1;
        $url->save();
    }

    public static function generateToken(): string
    {
        return Str::random(6);
    }
}
