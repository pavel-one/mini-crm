<?php

if (!function_exists('formatSize')) {
    function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function timeFormat($time)
    {
        return date("H:i:s", mktime(0, 0, $time));
    }

    function price_format($price)
    {
        return number_format($price, 0, '.', ' ');
    }

    function diskFilePath($disk, $filename)
    {
        return config('filesystems.disks.' . $disk . '.root') . '/' . $filename;
    }

    function getUserPhoto($id = false)
    {
        if ($id) {
            $user = \App\User::find($id);
        } else {
            $user = Auth::user();
        }
        if (!$user->photo) {
            $url = asset('/no-photo.png');
        } else {
            $url = asset('storage/'.$user->photo);
        }
        return $url;
    }
}