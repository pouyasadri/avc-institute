@extends('layouts.admin')

@section('title', 'Modifier une catégorie')

@section('header', 'Modifier une catégorie')

@section('content')
    <div class="card mb-4">
        <div class="card-header">Catégorie #{{ $category->id }}</div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.blog.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Catégorie parente</label>
                    <select class="form-select" id="parent_id" name="parent_id">
                        <option value="">Aucune (niveau racine)</option>
                        @foreach($parents as $parent)
                            @if($parent->id !== $category->id)
                                @php
                                    $parentTranslation = $parent->getTranslation(app()->getLocale());
                                @endphp
                                <option value="{{ $parent->id }}"
                                        @selected(old('parent_id', $category->parent_id) == $parent->id)>
                                    {{ $parentTranslation->name ?? 'Category '.$parent->id }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    @foreach(config('localization.supported_locales', ['en', 'fr', 'fa']) as $index => $locale)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#locale-{{ $locale }}"
                                    type="button"
                                    role="tab">
                                {{ strtoupper($locale) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content border border-top-0 p-3 mb-3">
                    @foreach(config('localization.supported_locales', ['en', 'fr', 'fa']) as $index => $locale)
                        @php
                            $translation = $category->translations->firstWhere('locale', $locale);
                        @endphp
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                             id="locale-{{ $locale }}"
                             role="tabpanel">
                            <input type="hidden" name="translations[{{ $index }}][locale]" value="{{ $locale }}">

                            <div class="mb-3">
                                <label class="form-label" for="name_{{ $locale }}">Nom ({{ strtoupper($locale) }})</label>
                                <input type="text"
                                       class="form-control"
                                       id="name_{{ $locale }}"
                                       name="translations[{{ $index }}][name]"
                                       value="{{ old("translations.{$index}.name", $translation->name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="slug_{{ $locale }}">Slug ({{ strtoupper($locale) }})</label>
                                <input type="text"
                                       class="form-control"
                                       id="slug_{{ $locale }}"
                                       name="translations[{{ $index }}][slug]"
                                       value="{{ old("translations.{$index}.slug", $translation->slug ?? '') }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection
