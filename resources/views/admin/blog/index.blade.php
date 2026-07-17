@extends('layouts.admin')

@section('title', 'Gestion des Blogs')

@section('header', 'Gestion des Blogs')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> Liste des Blogs</span>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">Ajouter un Blog</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">ID</th>
                            <th scope="col" style="width: 10%;">Image</th>
                            <th scope="col" style="width: 30%;">Titre</th>
                            <th scope="col" style="width: 15%;">Catégorie</th>
                            <th scope="col" style="width: 10%;">Statut</th>
                            <th scope="col" style="width: 15%;">Langues</th>
                            <th scope="col" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $blog->id }}</td>
                                <td>
                                    @if($blog->main_image)
                                        <img src="{{ $blog->main_image_url }}" alt="Img" class="table-img">
                                    @else
                                        <span class="badge bg-light text-secondary">Aucune</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @php
                                        $adminLocale = app()->getLocale();
                                        // 1. Try Admin Locale
                                        $translation = $blog->translations->firstWhere('locale', $adminLocale);
                                        // 2. Try Fallback Locale (EN)
                                        if (!$translation) {
                                            $translation = $blog->translations->firstWhere('locale', config('app.fallback_locale'));
                                        }
                                        // 3. Try Farsi (FA) specifically as it is a main language
                                        if (!$translation) {
                                            $translation = $blog->translations->firstWhere('locale', 'fa');
                                        }
                                        // 4. Try ANY translation
                                        if (!$translation) {
                                            $translation = $blog->translations->first();
                                        }
                                    @endphp
                                    
                                    {{ $translation->title ?? 'Sans titre (ID: ' . $blog->id . ')' }}
                                    
                                    @if($translation && $translation->locale !== $adminLocale)
                                        <small class="text-muted ms-1">({{ strtoupper($translation->locale) }})</small>
                                    @endif

                                    @if(!$translation)
                                        <small class="text-danger d-block fst-italic">(Aucune traduction)</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border border-secondary">
                                        @if($blog->category)
                                            @php
                                                $catTranslation = $blog->category->translations->firstWhere('locale', $adminLocale);
                                                if (!$catTranslation) {
                                                    $catTranslation = $blog->category->translations->firstWhere('locale', config('app.fallback_locale'));
                                                }
                                                if (!$catTranslation) {
                                                    $catTranslation = $blog->category->translations->first();
                                                }
                                            @endphp
                                            {{ $catTranslation->name ?? 'Catégorie sans nom' }}
                                            @if($catTranslation && $catTranslation->locale !== $adminLocale)
                                                <small class="text-muted ms-1">({{ strtoupper($catTranslation->locale) }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted fst-italic">Non classé</span>
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($blog->published_at && $blog->published_at <= now())
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Publié</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Brouillon</span>
                                    @endif
                                    @if($blog->trashed())
                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-1 px-3 py-2 rounded-pill">Supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($blog->translations as $trans)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary me-1">{{ strtoupper($trans->locale) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn-action btn-action-edit" data-bs-toggle="tooltip" title="Modifier">
                                            <i class='bx bxs-edit fs-6'></i>
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce blog ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" data-bs-toggle="tooltip" title="Supprimer">
                                                <i class='bx bxs-trash fs-6'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun blog trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection