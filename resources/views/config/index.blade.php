@once
<script>
    partytown = {
        forward: @json(\JustBetter\StatamicPartytown\Facades\Partytown::compileForwardFunctions()),
        mainWindowAccessors: [],
        loadScriptsOnMainThread: @json(\JustBetter\StatamicPartytown\Facades\Partytown::getLoadScriptsOnMainThread()),
        debug: {{ var_export(config('app.debug'), true) }},
        resolveUrl: function (url, location, type) {
            if (@json(\JustBetter\StatamicPartytown\Facades\Partytown::getDomainWhitelist()).includes(url.host)) {
                return @json(route('statamic-partytown::proxy', ['url' => '/'], false) . '/') + url.href;
            }
            return url
        },
        resolveSendBeaconRequestParameters: function (url) {
            return url.hostname.includes('analytics.google') || url.hostname.includes('google-analytics')
                ? { keepalive: false }
                : {};
        },
    }
</script>
@include('statamic-partytown::config.tag-assistant')
@endonce
