<?php defined('SYSPATH') or die('No direct script access.');

class Environment
{

    public static function level()
    {
        switch (Kohana::$environment) {
            case Kohana::PRODUCTION:
                return 'prod';
                break;
            case Kohana::STAGING:
                return 'stage';
                break;
            case Kohana::DEVELOPMENT:
                return 'dev';
                break;
            default:
                return '???';
        }
    }
}
