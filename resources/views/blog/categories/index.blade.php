@extends('layouts.main')
@section('title', __('Blog Categories'))
@section('content')
    <div class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h1>{{ __('Blog Categories') }}</h1>
                <ul>
                    <li><a href="{{ route('index', ['locale' => $locale]) }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('Blog') }}</a></li>
                    <li>{{ __('Blog Categories') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="ptb-100">
        <div class="container">
            <p class="mb-4">
                @if($locale === 'fa')
                    دسته‌بندی‌های مقالات ما درباره مهاجرت، ویزا، تحصیل و زندگی در فرانسه را در اینجا ببینید.
                @elseif($locale === 'fr')
                    Découvrez nos catégories d’articles sur l’immigration, les visas, les études et la vie en France.
                @else
                    Explore our article categories on immigration, visas, studying, and life in France.
                @endif
            </p>

            <div class="row">
                @forelse($categories as $category)
                    @php
                        $translation = $category->getTranslation($locale);
                        $parentTranslation = $category->parent?->getTranslation($locale);
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="h-100">
                            <h2 class="h5 mb-2">{{ $translation->name ?? __('Category') }}</h2>
                            @if($parentTranslation)
                                <p class="text-muted small mb-2">{{ $parentTranslation->name }}</p>
                            @endif
                            <p class="mb-0">
                                <a href="{{ route('blog.index', ['locale' => $locale]) }}">
                                    {{ $category->posts_count }} {{ __('Posts') }}
                                </a>
                            </p>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p>{{ __('No categories found.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
