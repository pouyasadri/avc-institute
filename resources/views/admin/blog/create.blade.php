@extends('layouts.admin')

@section('title', 'Ajouter un Blog')

@section('header', 'Ajouter un Blog')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $code }}"
                                    role="tabpanel" aria-labelledby="{{ $code }}-tab">
                                    <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}">

                                    <div class="mb-3">
                                        <label class="form-label">Titre ({{ strtoupper($code) }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="translations[{{ $code }}][title]"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug ({{ strtoupper($code) }})</label>
                                        <input type="text" class="form-control" name="translations[{{ $code }}][slug]">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Extrait ({{ strtoupper($code) }})</label>
                                        <textarea class="form-control" name="translations[{{ $code }}][excerpt]"
                                            rows="3"></textarea>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Contenu ({{ strtoupper($code) }}) <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="translations[{{ $code }}][body]" rows="10"
                                            required></textarea>
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
                                            <option value="{{ $category->id }}">
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
                                    <input type="file" class="form-control" name="blog_main_image">
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_pinned" name="is_pinned"
                                        value="1">
                                    <label class="form-check-label" for="is_pinned">Épinglé</label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date de publication</label>
                                    <input type="datetime-local" class="form-control" name="published_at"
                                        value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (e.defaultPrevented) {
                    return;
                }
                form.querySelectorAll('textarea[name$="[body]"]').forEach(function(textarea) {
                    if (textarea.value && !textarea.hasAttribute('data-encoded')) {
                        try {
                            textarea.value = btoa(unescape(encodeURIComponent(textarea.value)));
                            textarea.setAttribute('data-encoded', 'true');
                        } catch (err) {
                            console.error('Failed to encode textarea:', err);
                        }
                    }
                });
            });
        }
    });
</script>
@endpush