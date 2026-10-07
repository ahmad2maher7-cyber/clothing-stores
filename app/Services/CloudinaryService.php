<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected static function getConfig(): array
    {
        return [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ];
    }

    /**
     * رفع صورة إلى Cloudinary باستخدام cURL مباشرة (Signed Upload)
     */
    public static function upload(UploadedFile $file, string $folder = 'general'): ?string
    {
        if ($file->getSize() === 0) {
            Log::warning('Cloudinary: empty file');
            return null;
        }

        $config = self::getConfig();

        if (!$config['cloud_name'] || !$config['api_key'] || !$config['api_secret']) {
            Log::error('Cloudinary: missing credentials in env');
            return null;
        }

        try {
            $timestamp = time();
            $folderPath = 'clothing-store/' . $folder;

            // التوقيع (Signature) — sha1
            $paramsToSign = "folder={$folderPath}&timestamp={$timestamp}";
            $signature = sha1($paramsToSign . $config['api_secret']);

            // إعداد cURL
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.cloudinary.com/v1_1/{$config['cloud_name']}/image/upload",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'file' => new \CURLFile($file->getRealPath()),
                    'api_key' => $config['api_key'],
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $folderPath,
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 60,
            ]);

            // على Windows — استخدم cacert.pem
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $caPath = 'C:\xampp\php\extras\ssl\cacert.pem';
                if (file_exists($caPath)) {
                    curl_setopt($ch, CURLOPT_CAINFO, $caPath);
                }
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('Cloudinary cURL error: ' . $curlError);
                return null;
            }

            $data = json_decode($response, true);

            if ($httpCode === 200 && isset($data['public_id'])) {
                Log::info('Cloudinary upload OK', ['public_id' => $data['public_id']]);
                return $data['public_id'];
            }

            Log::error('Cloudinary upload failed', [
                'http_code' => $httpCode,
                'response' => $response,
            ]);
            return null;

        } catch (\Throwable $e) {
            Log::error('Cloudinary exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * حذف صورة
     */
    public static function delete(?string $publicId): bool
    {
        if (empty($publicId) || self::isLocalPath($publicId)) {
            return false;
        }

        $config = self::getConfig();
        $timestamp = time();
        $paramsToSign = "public_id={$publicId}&timestamp={$timestamp}";
        $signature = sha1($paramsToSign . $config['api_secret']);

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.cloudinary.com/v1_1/{$config['cloud_name']}/image/destroy",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'public_id' => $publicId,
                    'api_key' => $config['api_key'],
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $caPath = 'C:\xampp\php\extras\ssl\cacert.pem';
                if (file_exists($caPath)) {
                    curl_setopt($ch, CURLOPT_CAINFO, $caPath);
                }
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;

        } catch (\Throwable $e) {
            Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * رابط الصورة
     */
    public static function url(?string $publicId): ?string
    {
        if (empty($publicId)) {
            return null;
        }

        if (self::isLocalPath($publicId)) {
            return asset('storage/' . $publicId);
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$publicId}";
    }

    /**
     * Thumbnail
     */
    public static function thumbnail(?string $publicId, int $width = 400, int $height = 500): ?string
    {
        if (empty($publicId)) {
            return null;
        }

        if (self::isLocalPath($publicId)) {
            return asset('storage/' . $publicId);
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return "https://res.cloudinary.com/{$cloudName}/image/upload/w_{$width},h_{$height},c_fill/{$publicId}";
    }

    protected static function isLocalPath(string $path): bool
    {
        return str_starts_with($path, 'stores/')
            || str_starts_with($path, 'products/')
            || str_starts_with($path, 'avatars/');
    }
}