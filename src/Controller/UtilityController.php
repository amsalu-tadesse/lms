<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilityController extends AbstractController
{

    public static function getMessage($code)
    {
        $message = '';
        switch ($code) {
            case '1451':
                # code...
                $message = 'Please try to delete items related to this record first.';
                break;

            default:
                # code...
                break;
        }

        return $message;
    }

}
