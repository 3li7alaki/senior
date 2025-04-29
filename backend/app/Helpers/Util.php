<?php

namespace App\Helpers;

use App\Models\Status;
use Carbon\Carbon;

class Util
{
    public static function deleteFile($file): bool
    {
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }

    public static function getStatusId($status): int
    {
        return Status::query()->where('type', $status->value)->firstOrFail()->id;
    }

    public static function getDayName($date, $locale): string
    {
        $day = Carbon::parse($date)->format('l');
        if ($locale == 'en') {
            return $day;
        }
        return match ($day) {
            'Sunday' => 'الأحد',
            'Monday' => 'الإثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
            default => $day,
        };
    }

    public static function convertNullStringsToNull($input): array
    {
        foreach ($input as $key => $value) {
            if ($value === 'null') {
                $input[$key] = null;
            } elseif (is_array($value)) {
                $input[$key] = self::convertNullStringsToNull($value);
            }
        }
        return $input;
    }
}
