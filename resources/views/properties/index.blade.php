@extends('layout')

@section('title', __('property/index.title'))
@section("keywords", __('property/index.keywords'))
@section('description', __('property/index.description'))

@push('styles')
    <style>
        /* Active state for Tag Cloud to match theme hover */
        .widget_tag_cloud .tagcloud a.active {
            background-color: #0F3A80;
            color: #ffffff;
            border-color: #0F3A80;
        }

        /* Active state for Categories to match theme hover */
        .widget_categories ul li a.active {
            color: #0F3A80;
            font-weight: bold;
        }
        .widget_categories ul li a.active::before {
            content: "✓ ";
            margin-right: 5px;
        }

        /* Ensure Nice Select matches theme and handles long text */
        .nice-select {
            float: none;
            display: inline-block;
            border: 1px solid #eeeeee;
            border-radius: 0;
            height: 40px;
            line-height: 38px;
            padding-left: 15px;
            padding-right: 30px;
            min-width: 200px; /* Wider for translations */
            width: auto;
        }
        .nice-select .list {
            width: 100%;
            border-radius: 0;
            margin-top: 1px;
        }
        /* RTL Support for Nice Select */
        html[dir="rtl"] .nice-select {
            padding-left: 30px;
            padding-right: 15px;
            text-align: right;
        }
        html[dir="rtl"] .nice-select:after {
            right: auto;
            left: 12px;
        }
        html[dir="rtl"] .nice-select .option {
            text-align: right;
            padding-right: 18px;
            padding-left: 29px;
        }

        /* Fix Header Height - prevent full page expansion */
        .page-title-area {
            height: auto !important;
        }
    </style>
@endpush

@section('content')
    <!-- Start Page Title Area -->
    <div class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h2>{{ __('property/index.page_heading') }}</h2>
                <ul>
                    <li>
                        <a href="{{ route('index', ['locale' => app()->getLocale()]) }}">
                            {{ __('property/index.breadcrumb_home') }}
                        </a>
                    </li>
                    <li>{{ __('property/index.breadcrumb_properties') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Title Area -->

    <!-- Start Property Area -->
    <section class="news-details-area ptb-100">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filter -->
                <div class="col-lg-4 col-md-12 mb-5 mb-lg-0">
                    <aside class="widget-area">
                        <form action="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" method="GET" id="filter-form">
                            @if(request('trashed'))
                                <input type="hidden" name="trashed" value="true">
                            @endif
                            <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                            <div class="widget widget_search">
                                <h3 class="widget-title">
                                    {{ __('property/index.filter_properties') }}
                                    <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" 
                                       class="float-end" style="font-size: 0.8rem; font-weight: normal; margin-top: 5px; color: #dc3545;">
                                        {{ __('property/index.clear_filters') }}
                                    </a>
                                </h3>

                                <!-- Price Range -->
                                <div class="mb-4">
                                    <label class="mb-2" style="color: #666; font-weight: 600;">{{ __('property/index.price_range') }}</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="search-field" name="min_price" 
                                                   min="0" max="{{ $maxPrice }}" step="1000"
                                                   placeholder="{{ __('property/index.min_label') }}" 
                                                   value="{{ request('min_price') }}"
                                                   style="width: 100%;">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="search-field" name="max_price" 
                                                   min="0" max="{{ $maxPrice }}" step="1000"
                                                   placeholder="{{ __('property/index.max_label') }}" 
                                                   value="{{ request('max_price') }}"
                                                   style="width: 100%;">
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                        {{ __('property/index.price_range') }}: 0 - {{ number_format($maxPrice) }}
                                    </small>
                                </div>
                                <button type="submit" class="default-btn w-100">
                                    {{ __('property/index.update_results') }}
                                    <span></span>
                                </button>
                            </div>

                            <!-- Room Count -> Tag Cloud -->
                            <div class="widget widget_tag_cloud">
                                <h3 class="widget-title">{{ __('property/index.number_of_rooms') }}</h3>
                                <div class="tagcloud">
                                    @for($i = 1; $i <= $maxRoomCount; $i++)
                                        @php
                                            $isActive = in_array($i, request('room_count', []));
                                        @endphp
                                        <a href="#" onclick="toggleFilter('room_{{ $i }}'); return false;" 
                                           class="{{ $isActive ? 'active' : '' }}">
                                           {{ $i }}+
                                        </a>
                                        <input type="checkbox" id="room_{{ $i }}" name="room_count[]" value="{{ $i }}" 
                                               {{ $isActive ? 'checked' : '' }} style="display:none;">
                                    @endfor
                                </div>
                            </div>

                            <!-- Garage Count -> Tag Cloud -->
                            <div class="widget widget_tag_cloud">
                                <h3 class="widget-title">{{ __('property/index.garages_label') }}</h3>
                                <div class="tagcloud">
                                    @for($i = 1; $i <= $maxGarageCount; $i++)
                                        @php
                                            $isActive = in_array($i, request('garage_count', []));
                                        @endphp
                                        <a href="#" onclick="toggleFilter('garage_{{ $i }}'); return false;"
                                           class="{{ $isActive ? 'active' : '' }}">
                                           {{ $i }}
                                        </a>
                                        <input type="checkbox" id="garage_{{ $i }}" name="garage_count[]" value="{{ $i }}" 
                                               {{ $isActive ? 'checked' : '' }} style="display:none;">
                                    @endfor
                                </div>
                            </div>

                            <!-- Regions -> Categories -->
                            @if(count($regions) > 0)
                                <div class="widget widget_categories">
                                    <h3 class="widget-title">{{ __('property/index.region_label') }}</h3>
                                    <ul>
                                        @foreach($regions as $region)
                                            @php
                                                $slug = Str::slug($region);
                                                $isActive = in_array($region, request('regions', []));
                                            @endphp
                                            <li>
                                                <a href="#" onclick="toggleFilter('region_{{ $slug }}'); return false;"
                                                   class="{{ $isActive ? 'active' : '' }}">
                                                    {{ $region }}
                                                </a>
                                                <input type="checkbox" id="region_{{ $slug }}" name="regions[]" value="{{ $region }}" 
                                                       {{ $isActive ? 'checked' : '' }} style="display:none;">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </form>
                    </aside>
                </div>

                <!-- Main Content -->
                <div class="col-lg-8 col-md-12">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                            {{ session('error') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Toolbar -->
                    <!-- Toolbar -->
                    <div class="widget-area mb-4">
                        <div class="widget p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-5 col-md-6">
                                     <span class="fw-bold">{{ $properties->total() }}</span> {{ __('property/index.properties_found') }}
                                </div>

                                <div class="col-lg-7 col-md-6 mt-3 mt-md-0">
                                    <div class="d-flex align-items-center justify-content-md-end flex-wrap gap-2">
                                        @auth
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('properties.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary btn-sm px-3">
                                                    <i class="bx bx-plus"></i> {{ __('property/index.add') }}
                                                </a>
                                                @if(!$includeTrashed)
                                                    <a href="?trashed=true" class="btn btn-outline-secondary btn-sm px-3">
                                                        <i class="bx bx-trash"></i> {{ __('property/index.trashed') }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary btn-sm px-3">
                                                        <i class="bx bx-list-ul"></i> {{ __('property/index.active') }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endauth

                                        <div class="d-flex align-items-center ms-md-2">
                                            <label for="sort-select" class="text-muted small me-3 mb-0" style="white-space: nowrap;">{{ __('property/index.sort_by') }}:</label>
                                            <select name="sort" id="sort-select" 
                                                    onchange="document.getElementById('filter-form').submit();">
                                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('property/index.newest') }}</option>
                                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('property/index.price_asc') }}</option>
                                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('property/index.price_desc') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($properties as $property)
                            @php
                                $translation = $property->getTranslation(app()->getLocale());
                                $cardBgUrl = $property->main_image
                                    ? \Storage::url($property->main_image)
                                    : 'https://via.placeholder.com/600x400';
                            @endphp
                            <div class="col-lg-6 col-md-6">
                                <div class="single-restaurants"
                                     style="background-image: url('{{ $cardBgUrl }}'); height: 400px; margin-bottom: 30px;">

                                    <i class="restaurants-icon flaticon-expand"></i>
                                    <span>{{ Str::limit($translation->name ?? __('property/index.no_name'), 30) }}</span>
                                    <p>
                                        {{ $property->city }}
                                        | €{{ number_format($property->price, 0, ',', '.') }}
                                    </p>

                                    <div class="property-features mb-3 text-white">
                                        <small>
                                            <i class="bx bx-bed"></i> {{ $property->rooms }} {{ __('property/index.rooms') }}
                                            &nbsp;|&nbsp;
                                            <i class="bx bx-car"></i> {{ $property->garages }} {{ __('property/index.garages_label') }}
                                        </small>
                                    </div>

                                    @if($property->status !== 'available')
                                        <span class="badge badge-warning mb-3 d-inline-block">{{ ucfirst($property->status) }}</span>
                                    @endif

                                    <a href="{{ route('properties.show', ['locale' => app()->getLocale(), 'id' => $property->id]) }}" class="default-btn">
                                        {{ __('property/index.read_more') }}
                                        <i class="flaticon-right-arrow"></i>
                                    </a>

                                    @auth
                                        <div class="mt-3">
                                            <a href="{{ route('properties.edit', ['locale' => app()->getLocale(), 'id' => $property->id]) }}" class="btn btn-sm btn-light">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info py-4 text-center">
                                    <i class="bx bx-info-circle fs-4 mb-2 d-block"></i>
                                    {{ __('property/index.no_properties_found') }}
                                    <div class="mt-3">
                                        <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" class="btn btn-sm btn-outline-primary">
                                            {{ __('property/index.clear_all_filters') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="col-lg-12 col-md-12 mt-4 text-center">
                        {{ $properties->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function toggleFilter(elementId) {
        var checkbox = document.getElementById(elementId);
        if(checkbox) {
            checkbox.checked = !checkbox.checked;
            document.getElementById('filter-form').submit();
        }
    }
</script>
