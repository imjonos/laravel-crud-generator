<?php

if (!function_exists('getSetting')) {

    /**
     * Get setting value by name
     * @param string $name
     * @return string
     */
    function getSetting(string $name): string
    {
        $result = '';
        $setting = \Nos\CRUD\Setting::ofName($name)->first();
        if ($setting) {
            $result = $setting->value;
        }
        return $result;
    }
}

if (!function_exists('setSetting')) {

    /**
     * Set setting value by name
     * @param string $name
     * @param string $value
     * @return bool
     */
    function setSetting(string $name, string $value): bool
    {
        $result = false;
        $setting = \Nos\CRUD\Setting::ofName($name)->first();
        if ($setting) {
            try {
                $setting->update(['value' => $value]);
                $result = true;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Set Setting error: '.$e->getMessage());
            }
        }
        return $result;
    }
}
