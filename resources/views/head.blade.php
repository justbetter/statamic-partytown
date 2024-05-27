@include('statamic-partytown::config.index')
@include('statamic-partytown::script')

@if(isset($partytown_settings))
    @foreach ($partytown_settings?->scripts ?? [] as $script)
        @if(!$script?->enable)
            @continue
        @endif
        {!! $script->enable_partytown ? \JustBetter\StatamicPartytown\Facades\Partytown::scriptsToPartytown($script->script) : $script->script !!}
    @endforeach
@endif
