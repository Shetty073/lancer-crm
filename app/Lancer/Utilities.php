<?php

namespace App\Lancer;

class Utilities
{
    public const CURRENCY_SYMBOL = '₹';
    public const SALES_EMAIL = 'sales@proprays.com';
    public const SALES_RECEIVER_NAME = 'Proprays Sales';

    public static function getLeadDetailsEndpoint($leadgen_id): string
    {
        return 'https://graph.facebook.com/v11.0/' . $leadgen_id . '?access_token=' . env('FB_ACCESS_TOKEN');
    }

    public static function getEnquiryStatusStyle($status): string
    {
        return match ($status) {
            1 => 'px-2 py-2 badge badge-warning',
            2 => 'px-2 py-2 badge badge-primary',
            3 => 'px-2 py-2 badge badge-secondary',
            4 => 'px-2 py-2 badge badge-danger',
            default => 'px-2 py-2 badge badge-success',
        };
    }

    public static function getClientStatus($status): string
    {
        return match ($status) {
            1 => 'Active',
            default => 'Inactive',
        };
    }

    public static function getClientStatusStyle($status): string
    {
        return match ($status) {
            1 => 'px-2 py-2 badge badge-success',
            default => 'px-2 py-2 badge badge-danger',
        };
    }

    public static function numberReadableIndianFormat($num, $precision = 2): string
    {
        $num_format = $num;
        $suffix = '';
        if ($num >= 10000000) {
            $num_format = number_format($num/10000000, $precision);
            $suffix = 'Cr';
        } else if ($num >= 100000) {
            $num_format = number_format($num / 100000, $precision);
            $suffix = 'L';
        } else if ($num >= 1000) {
            $num_format = number_format($num / 1000, $precision);
            $suffix = 'K';
        }

        return $num_format . $suffix;
    }
}
