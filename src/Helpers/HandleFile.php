<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HandleFile
{
    public static function handle($dto)
    {
        $objects = [];
        foreach ($dto as $key => $value) {
            if (is_array($value)) {
                $objects[$key] = self::handle($value);
            } else {
                $objects[$key] = self::handleUrl($value);
            }
        }
        return $objects;
    }

    protected static function handleUrl($val)
    {
        $exploded = explode('.', $val);
        $extension = end($exploded);
        if (in_array($extension, ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'ico', 'tif', 'tiff', 'webp', 'heif']) && filter_var($val, FILTER_VALIDATE_URL) === false) {
            return Storage::url($val);
        }
        return $val;
    }

    public static function normalize($val)
    {
        $objects = [];
        foreach ($val as $key => $value) {
            if (is_array($value)) {
                $objects[$key] = self::normalize($value);
            } else {
                $objects[$key] = self::removeBaseUrl($value);
            }
        }
        return $objects;
    }

    protected static function removeBaseUrl($val)
    {
        if (Str::contains($val, Storage::url('/'))) {
            return Str::remove(Storage::url('/'), $val);
        }
        return $val;
    }
}
