@props(['items' => []])

<nav aria-label="breadcrumb" class="premium-breadcrumb">
    <ul>
        @foreach($items as $index => $item)
            @if(isset($item['url']))
                <li>
                    <a href="{{ $item['url'] }}">
                        @if($index === 0 && !isset($item['icon']))
                            <i class="bx bx-home-alt"></i>
                        @elseif(isset($item['icon']))
                            <i class="{{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['label'] }}
                    </a>
                </li>
            @else
                <li aria-current="page">{{ $item['label'] }}</li>
            @endif

            @if(!$loop->last)
                <li class="separator">
                    <i
                        class="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'bx bx-chevron-left' : 'bx bx-chevron-right' }}"></i>
                </li>
            @endif
        @endforeach
    </ul>
</nav>

@once
    @push('styles')
        <style>
            /* Premium Glassmorphism Breadcrumb */
            .premium-breadcrumb {
                display: inline-block;
                margin-bottom: 30px;
            }

            .premium-breadcrumb ul {
                display: inline-flex;
                align-items: center;
                background: rgba(15, 58, 128, 0.4);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                padding: 10px 24px !important;
                border-radius: 50px;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                margin: 0 !important;
                gap: 12px;
            }

            .premium-breadcrumb ul li {
                font-size: 15px !important;
                font-weight: 500 !important;
                color: rgba(255, 255, 255, 0.9) !important;
                display: flex;
                align-items: center;
                margin: 0 !important;
                position: relative;
            }

            /* Remove old inline separator from template */
            .page-title-area .page-title-content .premium-breadcrumb ul li::before {
                display: none !important;
            }

            .premium-breadcrumb ul li a {
                color: #ffffff !important;
                font-weight: 600;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .premium-breadcrumb ul li a:hover {
                color: #ff8c00 !important;
                text-shadow: 0 0 10px rgba(255, 140, 0, 0.5);
            }

            .premium-breadcrumb ul li.separator {
                color: rgba(255, 255, 255, 0.6) !important;
                font-size: 18px !important;
            }
        </style>
    @endpush
@endonce