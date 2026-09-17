@extends('layouts.admin')

@section('title', 'Commentaires')

@section('header', 'Gestion des Commentaires')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">ID</th>
                            <th scope="col" style="width: 15%;">Auteur</th>
                            <th scope="col" style="width: 22%;">Blog</th>
                            <th scope="col" style="width: 20%;">Message</th>
                            <th scope="col" style="width: 10%;">RGPD</th>
                            <th scope="col" style="width: 10%;">Statut</th>
                            <th scope="col" style="width: 10%;">Date</th>
                            <th scope="col" style="width: 8%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $comment->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $comment->name }}</div>
                                    <div class="small text-muted">{{ $comment->email }}</div>
                                </td>
                                <td>
                                    @if($comment->blog)
                                        <a href="{{ route('admin.blog.edit', $comment->blog->id) }}" target="_blank"
                                            class="text-decoration-none fw-bold">
                                            {{ Str::limit($comment->blog->getTranslation(app()->getLocale())->title ?? 'Sans titre', 30) }}
                                        </a>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Blog supprimé</span>
                                    @endif
                                </td>
                                <td class="text-muted fst-italic">"{{ Str::limit($comment->body, 60) }}"</td>
                                <td>
                                    @if($comment->gdpr_consent)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success" data-bs-toggle="tooltip" title="Consenti v{{ $comment->privacy_policy_version ?? '1.0' }} le {{ $comment->consent_given_at?->format('d/m/Y H:i') ?? $comment->created_at->format('d/m/Y H:i') }}">
                                            <i class='bx bx-check-shield'></i> v{{ $comment->privacy_policy_version ?? '1.0' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary" data-bs-toggle="tooltip" title="Donnée antérieure aux formulaires RGPD">
                                            <i class='bx bx-minus-circle'></i> Ancien
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($comment->is_approved)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Approuvé</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">En
                                            attente</span>
                                    @endif
                                </td>
                                <td class="text-nowrap text-secondary">
                                    {{ $comment->created_at->format('d/m/Y') }}<br><small>{{ $comment->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="btn-action {{ $comment->is_approved ? 'btn-action-disapprove' : 'btn-action-approve' }}"
                                                title="{{ $comment->is_approved ? 'Désapprouver' : 'Approuver' }}"
                                                data-bs-toggle="tooltip">
                                                <i class='bx {{ $comment->is_approved ? 'bx-x fs-6' : 'bx-check fs-6' }}'></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Supprimer"
                                                data-bs-toggle="tooltip">
                                                <i class='bx bxs-trash fs-6'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class='bx bx-message-rounded-x fs-1 d-block mb-2'></i>
                                    Aucun commentaire trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@endsection