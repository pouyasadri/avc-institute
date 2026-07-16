@extends('layout')
@section('title', __('Edit Blog Post'))
@section('content')
    <div class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h2>{{ __('Edit Blog Post') }}</h2>
                <ul>
                    <li><a href="{{ route('index', ['locale' => app()->getLocale()]) }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}">{{ __('Blog') }}</a></li>
                    <li>{{ __('Edit') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="ptb-100">
        <div class="container">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <form action="{{ route('blog.update', ['locale' => app()->getLocale(), 'blog' => $blog->id]) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>{{ __('Blog Post Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">{{ __('Category') }}</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">{{ __('Select category') }}</option>
                                        @foreach($categories as $cat)
                                            @php
                                                $catTranslation = $cat->translations->where('locale', app()->getLocale())->first();
                                            @endphp
                                            <option value="{{ $cat->id }}"
                                                    {{ old('category_id', $blog->category_id) === $cat->id ? 'selected' : '' }}>
                                                {{ $catTranslation->name ?? 'Category ' . $cat->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="published_at">{{ __('Published At') }}</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at"
                                           value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_pinned" name="is_pinned"
                                       value="1" {{ old('is_pinned', $blog->is_pinned) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_pinned">
                                    {{ __('Pin this post (featured)') }}
                                </label>
                            </div>
                        </div>

                        {{-- Current Main Image --}}
                        @if($blog->main_image)
                            <div class="form-group">
                                <label>{{ __('Current Main Image') }}</label>
                                <div>
                                    <img src="{{ \Storage::disk('s3')->url($blog->main_image) }}"
                                         alt="Main image"
                                         style="max-height: 200px;"
                                         class="img-thumbnail">
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="blog_main_image">{{ __('Replace Main Image') }}</label>
                            <input type="file" class="form-control-file" id="blog_main_image" name="blog_main_image"
                                   accept="image/*">
                            <small class="form-text text-muted">{{ __('Max 10MB, leave empty to keep current image') }}</small>
                        </div>
                    </div>
                </div>

                {{-- Translations --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>{{ __('Translations') }}</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($locales as $index => $locale)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                       data-toggle="tab"
                                       href="#locale-{{ $locale }}"
                                       role="tab">
                                        {{ strtoupper($locale) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3">
                            @foreach($locales as $index => $locale)
                                @php
                                    $translation = $blog->translations->where('locale', $locale)->first();
                                @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                     id="locale-{{ $locale }}"
                                     role="tabpanel">
                                    <input type="hidden" name="translations[{{ $index }}][locale]" value="{{ $locale }}">

                                    <div class="form-group">
                                        <label for="title_{{ $locale }}">{{ __('Title') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text"
                                               class="form-control"
                                               id="title_{{ $locale }}"
                                               name="translations[{{ $index }}][title]"
                                               value="{{ old("translations.{$index}.title", $translation->title ?? '') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="slug_{{ $locale }}">{{ __('Slug') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text"
                                               class="form-control"
                                               id="slug_{{ $locale }}"
                                               name="translations[{{ $index }}][slug]"
                                               value="{{ old("translations.{$index}.slug", $translation->slug ?? '') }}">
                                        <small class="form-text text-muted">{{ __('Leave empty to auto-generate') }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="excerpt_{{ $locale }}">{{ __('Excerpt') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control"
                                                  id="excerpt_{{ $locale }}"
                                                  name="translations[{{ $index }}][excerpt]"
                                                  rows="3">{{ old("translations.{$index}.excerpt", $translation->excerpt ?? '') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="body_{{ $locale }}">{{ __('Content') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control tinymce"
                                                  id="body_{{ $locale }}"
                                                  name="translations[{{ $index }}][body]"
                                                  rows="15">{{ old("translations.{$index}.body", $translation->body ?? '') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}" class="btn btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> {{ __('Update Blog Post') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/9miszad6siqmvj7ab46z564zwoxop04p4dma99uso4yethkl/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
    @endpush
@endsection

