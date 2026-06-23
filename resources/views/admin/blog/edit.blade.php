@extends('layouts.admin')

@section('title', 'Modifier le Blog')

@section('header', 'Modifier le Blog')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs mb-3" id="langTabs" role="tablist">
                            @foreach($locales as $code)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $code }}-tab"
                                        data-bs-toggle="tab" data-bs-target="#{{ $code }}" type="button" role="tab"
                                        aria-controls="{{ $code }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ strtoupper($code) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="langTabsContent">
                            @foreach($locales as $code)
                                @php
                                    $translation = $blog->getTranslation($code, false);
                                @endphp
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $code }}"
                                    role="tabpanel" aria-labelledby="{{ $code }}-tab">
                                    <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}">

                                    <div class="mb-3">
                                        <label class="form-label">Titre ({{ strtoupper($code) }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="translations[{{ $code }}][title]"
                                            value="{{ $translation->title ?? '' }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug ({{ strtoupper($code) }})</label>
                                        <input type="text" class="form-control" name="translations[{{ $code }}][slug]"
                                            value="{{ $translation->slug ?? '' }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Extrait ({{ strtoupper($code) }})</label>
                                        <textarea class="form-control" name="translations[{{ $code }}][excerpt]"
                                            rows="3">{{ $translation->excerpt ?? '' }}</textarea>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Contenu ({{ strtoupper($code) }}) <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="translations[{{ $code }}][body]" rows="10"
                                            required>{{ $translation->body ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Paramètres</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Catégorie</label>
                                    <select class="form-select" name="category_id" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->getTranslation('fr')->name ?? '' }}
                                                / {{ $category->getTranslation('en')->name ?? '' }}
                                                / {{ $category->getTranslation('fa')->name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-muted">FR / EN / FA</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image Principale</label>
                                    @if($blog->blog_main_image)
                                        <div class="mb-2">
                                            <img src="{{ \Storage::url($blog->blog_main_image) }}" alt="Current Image"
                                                class="img-fluid rounded">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="blog_main_image">
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_pinned" name="is_pinned"
                                        value="1" {{ $blog->is_pinned ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_pinned">Épinglé</label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date de publication</label>
                                    <input type="datetime-local" class="form-control" name="published_at"
                                        value="{{ $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '' }}">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection