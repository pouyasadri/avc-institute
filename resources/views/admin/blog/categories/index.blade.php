@extends('layouts.admin')

@section('title', 'Catégories de blog')

@section('header', 'Catégories de blog')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bx bx-category me-1"></i> Liste des catégories</span>
            <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary btn-sm">Ajouter une catégorie</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Articles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            @php
                                $translation = $category->getTranslation(app()->getLocale());
                                $parentTranslation = $category->parent?->getTranslation(app()->getLocale());
                            @endphp
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $category->id }}</td>
                                <td>{{ $translation->name ?? 'Sans nom' }}</td>
                                <td><code>{{ $translation->slug ?? '-' }}</code></td>
                                <td>{{ $parentTranslation->name ?? '—' }}</td>
                                <td>{{ $category->posts_count }}</td>
                                <td>
                                    <a href="{{ route('admin.blog.categories.edit', $category) }}"
                                       class="btn-action btn-action-edit btn-sm"
                                       title="Modifier">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blog.categories.destroy', $category) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette catégorie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete btn-sm" title="Supprimer">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucune catégorie.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
