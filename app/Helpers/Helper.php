<?php

if (!function_exists("ageGrouping")) {

    function ageGrouping($ageType)
    {
        preg_match_all('!\d+!', $ageType, $rangeOfAges);

        foreach ($rangeOfAges[0] as $rangeOfAge) {
            switch ($rangeOfAge) {
                case $rangeOfAge < 17:
                    return 'child';
                    break;
                case $rangeOfAge >= 17 and $rangeOfAge < 30:
                    return 'young';
                    break;
                case $rangeOfAge >= 30 and $rangeOfAge < 49:
                    return 'middleAged';
                    break;
                case $rangeOfAge >= 49:
                    return 'senior';
                    break;
            }
        }
    }
}

if (!function_exists("uploadDecode64")) {

    function uploadDecode64($file, $path)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $file)) {
            $data = substr($file, strpos($file, ',') + 1);
            /* $data = base64_decode($data); */
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);

            $imageName = \Ramsey\Uuid\Uuid::uuid1()->toString() . '.jpeg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($path . $imageName, $data);
            $imagePath = $path . $imageName;
            return $imagePath;
        }
    }
}

if (!function_exists("classIconNotification")) {

    function classIconNotification($type)
    {
        if ($type == 'user') {
            return 'mdi mdi-information-outline';
        }

    }
}

if (!function_exists("generateQRCodeString")) {

    function generateQRCodeString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
