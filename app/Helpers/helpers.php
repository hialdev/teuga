<?php

use Illuminate\Support\Facades\Schema;

if (!function_exists('getModelAttributes')) {
    function getModelAttributes($modelName, $excepts)
    {
        $excepts = $excepts ?? [];
        // Validasi input nama model
        if (empty($modelName) || !preg_match('/^[a-zA-Z0-9_]+$/', $modelName)) {
            return [];
        }

        $modelClass = "\App\Models\\".$modelName;

        // Pastikan kelas model ada
        if (!class_exists($modelClass)) {
            return [];
        }

        // Buat instance model
        $model = new $modelClass();

        // Pastikan tabel model ada
        if (!Schema::hasTable($model->getTable())) {
            return [];
        }

        // Ambil nama tabel dan kolom
        $table = $model->getTable();
        $columns = Schema::getColumnListing($table);

        // Keluarkan kolom 'id' dari daftar
        return array_values(array_filter($columns, fn($column) => $column !== 'id' && !in_array($column, $excepts)));

    }
}

if (!function_exists('hideData')) {
    function hideData($data = '') {
        if (empty($data)) {
            return '';
        }

        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $parts = explode("@", $data);
            $name = $parts[0];
            $domain = $parts[1];

            $visibleNameLength = min(3, strlen($name));
            $hiddenName = str_repeat('*', max(0, strlen($name) - $visibleNameLength)) . substr($name, -$visibleNameLength);

            $domainParts = explode(".", $domain);
            $mainDomain = $domainParts[0] ?? '';
            $tld = $domainParts[1] ?? '';

            $visibleDomainLength = min(1, strlen($mainDomain));
            $hiddenDomain = '@' . str_repeat('*', max(0, strlen($mainDomain) - $visibleDomainLength)) . substr($mainDomain, -$visibleDomainLength) . ($tld ? '.' . $tld : '');

            return $hiddenName . $hiddenDomain;
        } elseif (ctype_digit($data) && strlen($data) >= 4) {
            return str_repeat('*', strlen($data) - 3) . substr($data, -3);
        }

        $visibleLength = min(3, strlen($data));
        return str_repeat('*', max(0, strlen($data) - $visibleLength)) . substr($data, -$visibleLength);
    }
}

if (!function_exists('convertPhone')) {
    function convertPhone($phone) {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) < 9) {
            return $phone; 
        }
        if (strpos($phone, '0') === 0) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}

if (!function_exists('toPascalCase')){
    function toPascalCase($string)
    {
        $words = explode('_', $string);
        $pascalCaseString = implode(' ', array_map('ucwords', $words));
        return $pascalCaseString;
    }
}

if (!function_exists('urlApp')) {
    function urlApp($code, $path = '')
    {
        if (!$path) $path = '';
        if (!class_exists(\App\Models\Application::class) || !Schema::hasTable('applications')) {
            return null;
        }

        $app = \App\Models\Application::where('code', $code)->first();
        return $app ? $app->url.$path : null;
    }
}

if (!function_exists('filePath')) {
    function filePath($data)
    {
        $path = urlApp('SSO','/storage/'.$data);
        return $path;
    }
}

if (!function_exists('setting')) {
    function setting($code)
    {
        // Cek apakah model Setting dan tabel settings ada
        if (!class_exists(\App\Models\Setting::class) || !Schema::hasTable('settings')) {
            return null;
        }

        $explode = explode('.', $code);

        // Pastikan array $explode memiliki setidaknya dua elemen
        if (count($explode) < 2) {
            return null;
        }

        $setting = \App\Models\Setting::where('group_key', $explode[0])
            ->where('key', $explode[1])
            ->first();

        return $setting->value ?? null;
    }
}


if(!function_exists('formatRupiah')){
    function formatRupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}

if(!function_exists('parseRupiah')){
    function parseRupiah($value){
        return (int) str_replace(['Rp', '.', ','], '', $value);
    }
}

if (!function_exists('generateCode')) {
    function generateCode($type, $number, $companyCode = null, $month = null, $year = null)
    {
        $companyCode = $companyCode ?? config('al.company')['code']; // Default ke config
        $month = $month ?? date('n'); // Default ke bulan sekarang
        $year = $year ?? date('y');  // Default ke tahun sekarang

        $formattedNumber = str_pad($number, 3, '0', STR_PAD_LEFT);
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanMonth = $romanMonths[$month - 1]; // Ambil berdasarkan indeks

        return "{$type}/{$formattedNumber}/{$companyCode}/{$romanMonth}/{$year}";
    }
}

if (!function_exists('isImage')) {
    function isImage($filePath)
    {
        $storagePath = storage_path('app/public/' . $filePath);

        // Check if the file exists
        if (!file_exists($storagePath)) {
            return false;
        }

        // Get MIME type of the file
        $mimeType = mime_content_type($storagePath);

        // Return true if MIME type starts with "image/"
        return strpos($mimeType, 'image/') === 0;
    }
}

if (!function_exists('isPdf')) {
    function isPdf($fileName)
    {
        return str_ends_with($fileName, '.pdf');
    }
}
