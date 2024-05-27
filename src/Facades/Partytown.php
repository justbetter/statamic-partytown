<?php

namespace JustBetter\StatamicPartytown\Facades;

use Illuminate\Support\Facades\Facade;
use JustBetter\StatamicPartytown\Partytown as PartytownSingleton;

/**
 * @method static string scriptsToPartytown($string)
 *
 * @see \JustBetter\StatamicPartytown\Partytown
 */
class Partytown extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PartytownSingleton::class;
    }
}
