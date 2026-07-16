@extends('layout')

@php
    $currentLocale = app()->getLocale();
    $seoService = app(\App\Services\SeoService::class);
    $seoService->forProperty($property, $currentLocale);
    
    $pageTitle = $translation->name ?? 'Property';
    $pageDescription = strip_tags(Str::limit($translation->description ?? '', 160));
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('content')
    <div class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h2>{{ $translation->name ?? 'Property' }}</h2>
                <ul>
                    <li><a href="{{ route('index', ['locale' => app()->getLocale()]) }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}">{{ __('Properties') }}</a></li>
                    <li>{{ $translation->name ?? 'Property' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="ptb-100">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    {{-- Main Image --}}
                    @if($property->main_image)
                        <img src="{{ \Storage::disk('s3')->url($property->main_image) }}"
                             class="img-fluid rounded mb-4"
                             alt="{{ $translation->name ?? 'Property' }}">
                    @endif

                    {{-- Gallery Images --}}
                    @if($property->images->count() > 0)
                        <div class="row mb-4">
                            @foreach($property->images as $image)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ \Storage::disk('s3')->url($image->path) }}"
                                         class="img-fluid rounded"
                                         alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="property-description">
                        <h3>{{ __('Description') }}</h3>
                        <div class="content">
                            {!! $translation->description ?? '' !!}
                        </div>
                    </div>

                    {{-- Location Details --}}
                    <div class="mt-4">
                        <h3>{{ __('Location') }}</h3>
                        <p>
                            @if($property->address_line)
                                {{ $property->address_line }}<br>
                            @endif
                            {{ $property->postal_code }} {{ $property->city }}<br>
                            @if($property->region)
                                {{ $property->region }},
                            @endif
                            {{ $property->country }}
                        </p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Property Details') }}</h4>

                            <div class="mb-3">
                                <h3 class="text-primary">€{{ number_format($property->price, 2) }}</h3>
                            </div>

                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>{{ __('Type') }}:</strong> {{ ucfirst($property->type) }}
                                </li>
                                <li class="mb-2">
                                    <strong>{{ __('Status') }}:</strong>
                                    <span class="badge badge-{{ $property->status === 'available' ? 'success' : 'warning' }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>{{ __('Rooms') }}:</strong> {{ $property->rooms }}
                                </li>
                                <li class="mb-2">
                                    <strong>{{ __('Garages') }}:</strong> {{ $property->garages }}
                                </li>
                            </ul>

                            @auth
                                <div class="mt-4">
                                    <a href="{{ route('properties.edit', ['locale' => app()->getLocale(), 'id' => $property->id]) }}"
                                       class="btn btn-warning btn-block">
                                        <i class="bx bx-edit"></i> {{ __('Edit Property') }}
                                    </a>
                                    @php($confirmDeleteMessage = addslashes(__('Are you sure?')))
                                    @php($destroyAction = \Illuminate\Support\Facades\Route::has('properties.destroy') ? route('properties.destroy', ['locale' => app()->getLocale(), 'id' => $property->id]) : '#')
                                    <form action="{{ $destroyAction }}"
                                           method="POST"
                                          onsubmit="return confirm('{{ $confirmDeleteMessage }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block mt-2">
                                            <i class="bx bx-trash"></i> {{ __('Delete Property') }}
                                        </button>
                                    </form>
                                </div>
                            @endauth

                            <div class="mt-4">
                                <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}"
                                   class="btn btn-secondary btn-block">
                                    <i class="bx bx-arrow-back"></i> {{ __('Back to Properties') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('json')
    {{-- Property Schema (as Product with Offer) --}}
    @php
        $propertySchema = new \App\Services\StructuredData\PropertySchema($property, $currentLocale);
    @endphp
    <x-seo.structured-data :schema="$propertySchema" />
    
    {{-- Breadcrumb Schema --}}
    @php
        $breadcrumbSchema = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('Home'), 'url' => route('index', ['locale' => $currentLocale])],
            ['name' => __('properties.name'), 'url' => route('properties.index', ['locale' => $currentLocale])],
            ['name' => $pageTitle, 'url' => route('properties.show', ['locale' => $currentLocale, 'id' => $property->id])],
        ]);
    @endphp
    <x-seo.structured-data :schema="$breadcrumbSchema" />
@endpush
