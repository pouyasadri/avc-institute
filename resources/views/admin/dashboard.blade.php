@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('header', 'Tableau de bord')

@section('content')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fs-4 fw-bold">{{ $stats['blogs'] }} Blogs</div>
                        <i class='bx bxs-news fs-1 opacity-50'></i>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.blog.index') }}"
                            class="text-white text-decoration-none d-flex align-items-center">
                            Gérer les articles <i class='bx bx-right-arrow-alt ms-2'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fs-4 fw-bold">{{ $stats['questions'] }} Questions</div>
                        <i class='bx bxs-help-circle fs-1 opacity-50'></i>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.questions.index') }}"
                            class="text-white text-decoration-none d-flex align-items-center">
                            Voir les questions <i class='bx bx-right-arrow-alt ms-2'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fs-4 fw-bold">{{ $stats['comments'] }} Avis</div>
                        <i class='bx bxs-comment fs-1 opacity-50'></i>
                    </div>
                    <div>
                        <small class="text-white opacity-75">{{ $stats['pending_comments'] }} en attente</small>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.comments.index') }}"
                            class="text-white text-decoration-none d-flex align-items-center">
                            Modérer <i class='bx bx-right-arrow-alt ms-2'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fs-4 fw-bold">{{ $stats['consultations'] }} Demandes</div>
                        <i class='bx bxs-user-voice fs-1 opacity-50'></i>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.consulting.index') }}"
                            class="text-white text-decoration-none d-flex align-items-center">
                            Voir les demandes <i class='bx bx-right-arrow-alt ms-2'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Recent Activity Placeholder -->
    <div class="row mt-4">
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Derniers Articles Ajoutés</h5>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-sm btn-light text-primary">Tout voir</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Titre</th>
                                    <th>Catégorie</th>
                                    <th>Date</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBlogs as $blog)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($blog->blog_main_image)
                                                    <img src="{{ \Storage::url($blog->blog_main_image) }}" class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded me-2 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                                                        <i class='bx bx-image text-secondary'></i>
                                                    </div>
                                                @endif
                                                <span class="fw-medium text-dark">
                                                    {{ Str::limit($blog->getTranslation(app()->getLocale())->title ?? $blog->getTranslation('en')->title ?? 'Sans titre', 40) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border border-secondary fw-normal">
                                                {{ $blog->category->getTranslation(app()->getLocale())->name ?? 'Non classé' }}
                                            </span>
                                        </td>
                                        <td class="text-secondary small">
                                            {{ $blog->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn-action btn-action-edit btn-sm" data-bs-toggle="tooltip" title="Modifier">
                                                <i class='bx bxs-edit'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Aucun article récent.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Actions Rapides</h5>
                </div>
                <div class="card-body px-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-primary text-start">
                            <i class='bx bx-plus-circle me-2'></i> nouvel Article
                        </a>
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-outline-secondary text-start">
                            <i class='bx bx-user-plus me-2'></i> Ajouter un Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection