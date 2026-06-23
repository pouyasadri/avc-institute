@extends('layout')
@section('title', __('Edit Property'))
@section('content')
    <div class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h2>{{ __('Edit Property') }}</h2>
                <ul>
                    <li><a href="{{ route('index', ['locale' => app()->getLocale()]) }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}">{{ __('Properties') }}</a></li>
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

            @php($updateAction = \Illuminate\Support\Facades\Route::has('properties.update') ? route('properties.update', ['locale' => app()->getLocale(), 'id' => $property->id]) : '#')
            <form action="{{ $updateAction }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>{{ __('Property Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">{{ __('Price') }} (EUR) *</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                                           value="{{ old('price', $property->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rooms">{{ __('Rooms') }}</label>
                                    <input type="number" class="form-control" id="rooms" name="rooms"
                                           value="{{ old('rooms', $property->rooms) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="garages">{{ __('Garages') }}</label>
                                    <input type="number" class="form-control" id="garages" name="garages"
                                           value="{{ old('garages', $property->garages) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">{{ __('Type') }} *</label>
                                    <select class="form-control" id="type" name="type" required>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('type', $property->type) === $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $property->status) === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address_line">{{ __('Address Line') }}</label>
                            <input type="text" class="form-control" id="address_line" name="address_line"
                                   value="{{ old('address_line', $property->address_line) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal_code">{{ __('Postal Code') }} *</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code"
                                           value="{{ old('postal_code', $property->postal_code) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">{{ __('City') }} *</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                           value="{{ old('city', $property->city) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="region">{{ __('Region') }}</label>
                                    <input type="text" class="form-control" id="region" name="region"
                                           value="{{ old('region', $property->region) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">{{ __('Country Code') }}</label>
                                    <input type="text" class="form-control" id="country" name="country"
                                           value="{{ old('country', $property->country) }}" maxlength="2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="published_at">{{ __('Published At') }}</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at"
                                           value="{{ old('published_at', $property->published_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Current Main Image --}}
                        @if($property->main_image)
                            <div class="form-group">
                                <label>{{ __('Current Main Image') }}</label>
                                <div>
                                    <img src="{{ \Storage::url($property->main_image) }}"
                                         alt="Main image"
                                         style="max-height: 200px;"
                                         class="img-thumbnail">
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="main_image">{{ __('Replace Main Image') }}</label>
                            <input type="file" class="form-control-file" id="main_image" name="main_image"
                                   accept="image/*">
                            <small class="form-text text-muted">{{ __('Max 10MB, leave empty to keep current image') }}</small>
                        </div>

                        {{-- Current Gallery Images --}}
                        @if($property->images->count() > 0)
                            <div class="form-group">
                                <label>{{ __('Current Gallery Images') }}</label>
                                <div class="row">
                                    @foreach($property->images as $image)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ \Storage::url($image->path) }}"
                                                     class="card-img-top"
                                                     alt="Gallery">
                                                <div class="card-body p-2">
                                                    @php($deleteImageAction = \Illuminate\Support\Facades\Route::has('properties.images.delete') ? route('properties.images.delete', ['locale' => app()->getLocale(), 'image' => $image->id]) : '#')
                                                    <form action="{{ $deleteImageAction }}"
                                                           method="POST"
                                                           @php($confirmDeleteImageMessage = addslashes(__('Delete this image?')))
                                                           onsubmit="return confirm('{{ $confirmDeleteImageMessage }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-block">
                                                            <i class="bx bx-trash"></i> {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="gallery_images">{{ __('Add More Gallery Images') }}</label>
                            <input type="file" class="form-control-file" id="gallery_images" name="gallery_images[]"
                                   accept="image/*" multiple>
                            <small class="form-text text-muted">{{ __('Max 10MB per image, multiple files allowed') }}</small>
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
                                    $translation = $property->translations->where('locale', $locale)->first();
                                @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                     id="locale-{{ $locale }}"
                                     role="tabpanel">
                                    <input type="hidden" name="translations[{{ $index }}][locale]" value="{{ $locale }}">

                                    <div class="form-group">
                                        <label for="name_{{ $locale }}">{{ __('Name') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text"
                                               class="form-control"
                                               id="name_{{ $locale }}"
                                               name="translations[{{ $index }}][name]"
                                               value="{{ old("translations.{$index}.name", $translation->name ?? '') }}">
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
                                        <label for="description_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control tinymce"
                                                  id="description_{{ $locale }}"
                                                  name="translations[{{ $index }}][description]"
                                                  rows="10">{{ old("translations.{$index}.description", $translation->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" class="btn btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> {{ __('Update Property') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
    @endpush
@endsection
