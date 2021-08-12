<?php

namespace App\Lancer;

class Utilities
{
    public const ORG_NAME = 'Proprays Realtors';
    public const CURRENCY_SYMBOL = '₹';
    public const SALES_EMAIL = 'sales@proprays.com';
    public const SALES_RECEIVER_NAME = 'Proprays Sales';

    public static function getLeadDetailsEndpoint($leadgen_id) {
        return 'https://graph.facebook.com/v11.0/' . $leadgen_id . '?access_token=' . env('FB_ACCESS_TOKEN');
    }

    public static function getEnquiryStatusStyle($status)
    {
        $enquiryStatusStyle = '';

        switch ($status) {
            case 1:
                $enquiryStatusStyle = 'rounded-full bg-yellow-500 text-white py-1 px-2';
                break;
            case 2:
                $enquiryStatusStyle = 'rounded-full bg-blue-500 text-white py-1 px-2';
                break;
            case 3:
                $enquiryStatusStyle = 'rounded-full bg-gray-500 text-white py-1 px-2';
                break;
            case 4:
                $enquiryStatusStyle = 'rounded-full bg-red-500 text-white py-1 px-2';
                break;

            default:
                $enquiryStatusStyle = 'rounded-full bg-green-500 text-white py-1 px-2';
                break;
        }

        return $enquiryStatusStyle;
    }

    public static function getClientStatus($status)
    {
        $enquiryStatusStyle = '';

        switch ($status) {
            case 1:
                $enquiryStatusStyle = 'Active';
                break;

            default:
                $enquiryStatusStyle = 'Inactive';
                break;
        }

        return $enquiryStatusStyle;
    }

    public static function getClientStatusStyle($status)
    {
        $enquiryStatusStyle = '';

        switch ($status) {
            case 1:
                $enquiryStatusStyle = 'rounded-full bg-green-500 text-white py-1 px-2';
                break;

            default:
                $enquiryStatusStyle = 'rounded-full bg-red-500 text-white py-1 px-2';
                break;
        }

        return $enquiryStatusStyle;
    }
}
