<footer role="contentinfo" class="dark">
    <div class="center center:wide">
        <div class="switcher">
            <div class="stack">
                <!-- Brand -->
                <a class="brand" rel="home" href="{{ localized_route('welcome') }}">
                    @if(locale() == 'en')
                    <x-tae-logo-mono-en role="presentation" class="logo" />
                    @elseif(locale() == 'fr')
                    <x-tae-logo-mono-fr role="presentation" class="logo" />
                    @endif
                    <span class="visually-hidden">{{ __('app.name') }}</span>
                </a>
                <nav aria-label="{{ __('secondary') }}">
                    <ul class="stack" role="list">
                        <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
                        {{-- TODO: Add glossary feature --}}
                        {{-- <li><a href="">{{ __('Glossary') }}</a></li> --}}
                        {{-- TODO: Add Terms of Service --}}
                        {{-- <li><a href="{{ localized_route('about.terms-of-service') }}">{{ __('Terms of Service') }}</a></li> --}}
                        {{-- TODO: Add Privacy Policy --}}
                        {{-- <li><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Privacy Policy') }}</a></li> --}}
                    </ul>
                </nav>
            </div>
            <div class="switcher grow:2">
                <div class="stack">
                    <h2>{{ __('Contact') }}</h2>
                    <address class="stack">
                    <h3>{{ __('Email') }}</h3>
                    <p><a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">{{ settings()->get('email', 'support@accessibilityexchange.ca') }}</a></p>
                    <h3>{!! __('Call, Text, or :vrs', ['vrs' => '<a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">' . __('VRS') . '</a>']) !!}</h3>
                    <p>{{ phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA') }}</p>
                    <h3>{{ __('Mailing Address') }}</h3>
                    @markdown{{ settings()->get('address', "The Accessibility Exchange ℅ IRIS  \n1 University Avenue, 3rd Floor  \nToronto, ON M5J 2P1") }}@endmarkdown
                    </address>
                </div>
                <nav class="stack" aria-labelledby="social">
                    <h2 id="social">{{ __('Social Media') }}</h2>
                    <ul class="stack" role="list">
                        <li><a rel="external" href="https://www.linkedin.com/company/the-accessibility-exchange/">LinkedIn</a></li>
                        <li><a rel="external" href="https://www.facebook.com/AccessXchange">Facebook</a></li>
                        <li><a rel="external" href="https://twitter.com/AccessXchange">Twitter</a></li>
                        <li><a rel="external" href="https://www.youtube.com/channel/UC-mIk4Xk04wF4urFSKZQOAA">YouTube</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
@livewireScripts()
