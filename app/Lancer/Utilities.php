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
                $enquiryStatusStyle = 'px-2 py-2 badge badge-warning';
                break;
            case 2:
                $enquiryStatusStyle = 'px-2 py-2 badge badge-primary';
                break;
            case 3:
                $enquiryStatusStyle = 'px-2 py-2 badge badge-secondary';
                break;
            case 4:
                $enquiryStatusStyle = 'px-2 py-2 badge badge-danger';
                break;

            default:
                $enquiryStatusStyle = 'px-2 py-2 badge badge-success';
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
                $enquiryStatusStyle = 'px-2 py-2 badge badge-success';
                break;

            default:
                $enquiryStatusStyle = 'px-2 py-2 badge badge-danger';
                break;
        }

        return $enquiryStatusStyle;
    }
}
