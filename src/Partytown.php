<?php

namespace JustBetter\StatamicPartytown;

use Statamic\Contracts\Globals\Variables;

class Partytown
{
    public ?Variables $config;

    public function scriptsToPartytown(string $string)
    {
        return preg_replace('/(?P<before>\<script(?:(?!type="[^"]+\/[^"]+")[^\>])*) ?(?P<type>[^ \>]*)?(?P<after>(?:(?!type="[^"]+\/[^"]+").)*)/', '\1 type="text/partytown"\3', $string);
    }

    public function compileForwardFunctions()
    {
        return collect($this->getForwardFunctions())
            ->mapWithKeys(fn ($value, $key) => [$key => is_string($value) ? [$value] : [$key, $value]])
            ->values();
    }

    public function getStatamicConfig(): ?Variables
    {
        return $this->config ??= \Statamic\Facades\GlobalVariables::whereSet('partytown_settings')->first();
    }

    public function getDomainWhitelist()
    {
        return array_merge($this->getStatamicConfig()?->domain_whitelist ?? [], config('statamic.partytown.domain_whitelist'));
    }

    public function getLoadScriptsOnMainThread()
    {
        return array_merge($this->getStatamicConfig()?->load_scripts_on_main_thread ?? [], config('statamic.partytown.load_scripts_on_main_thread'));
    }

    public function getForwardFunctions()
    {
        $functions = collect($this->getStatamicConfig()?->forward_functions ?? [])->mapWithKeys(fn($value, $key) => [$value['field_name'] => ['preserve_behavior' => $value['preserve_behavior']]]);

        return array_merge($functions->toArray(), config('statamic.partytown.forward_functions'));
    }
}
