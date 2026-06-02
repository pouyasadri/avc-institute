{{-- Language Switcher Component --}}
@props(['desktop' => false])

@if($desktop)
    {{-- Desktop Language Switcher --}}
    <div class="desktop-language-switcher">
        <div class="dropdown">
            <button class="lang-dropdown-toggle" type="button" aria-label="{{ __('layout.language') }}">
                <i class='bx bx-globe'></i>
                {{ strtoupper(app()->getLocale()) }}
                <i class='bx bx-chevron-down'></i>
            </button>
            <div class="lang-dropdown-menu">
                @foreach(config('seo.locales') as $localeCode => $localeData)
                    @php
                        $newUrl = url($localeCode);
                        $currentRoute = request()->route();

                        if ($currentRoute && $currentRoute->getName()) {
                            try {
                                $routeName = $currentRoute->getName();
                                $params = $currentRoute->parameters();
                                $paramNames = method_exists($currentRoute, 'parameterNames') ? $currentRoute->parameterNames() : [];
                                if (! empty($paramNames)) {
                                    $params = array_intersect_key($params, array_flip($paramNames));
                                }
                                $params['locale'] = $localeCode;
                                $newUrl = route($routeName, $params);
                            } catch (\Throwable $e) {
                                $segments = request()->segments();
                                $currentPath = count($segments) > 1 ? implode('/', array_slice($segments, 1)) : '';
                                $newUrl = url($localeCode . ($currentPath ? '/' . $currentPath : ''));
                            }
                        }
                    @endphp
                    <a href="{{ $newUrl }}" class="{{ app()->getLocale() === $localeCode ? 'active' : '' }}">
                        {{ $localeData['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@else
    {{-- Mobile Language Switcher --}}
    <div class="mobile-language-switcher">
        <span class="language-label">{{ __('layout.language') }}</span>
        <div class="language-buttons">
            @foreach(config('seo.locales') as $localeCode => $localeData)
                @php
                    $newUrl = url($localeCode);
                    $currentRoute = request()->route();

                    if ($currentRoute && $currentRoute->getName()) {
                        try {
                            $routeName = $currentRoute->getName();
                            $params = $currentRoute->parameters();
                            $paramNames = method_exists($currentRoute, 'parameterNames') ? $currentRoute->parameterNames() : [];
                            if (! empty($paramNames)) {
                                $params = array_intersect_key($params, array_flip($paramNames));
                            }
                            $params['locale'] = $localeCode;
                            $newUrl = route($routeName, $params);
                        } catch (\Throwable $e) {
                            $segments = request()->segments();
                            $currentPath = count($segments) > 1 ? implode('/', array_slice($segments, 1)) : '';
                            $newUrl = url($localeCode . ($currentPath ? '/' . $currentPath : ''));
                        }
                    }
                @endphp
                <a href="{{ $newUrl }}" class="lang-btn {{ app()->getLocale() === $localeCode ? 'active' : '' }}">
                    {{ $localeData['name'] }}
                </a>
            @endforeach
        </div>
    </div>
@endif
