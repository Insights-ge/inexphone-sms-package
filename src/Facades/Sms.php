<?php 

namespace Inexphone\Sms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor(): string 
    {
        return \Inexphone\Sms\SmsClient::class;
    }
}