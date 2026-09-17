@extends('layouts.admin')

@section('title', 'Questions')

@section('header', 'Gestion des Questions')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">ID</th>
                            <th scope="col" style="width: 15%;">Nom</th>
                            <th scope="col" style="width: 18%;">Email</th>
                            <th scope="col" style="width: 22%;">Sujet</th>
                            <th scope="col" style="width: 12%;">Page</th>
                            <th scope="col" style="width: 10%;">RGPD</th>
                            <th scope="col" style="width: 10%;">Date</th>
                            <th scope="col" style="width: 8%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $question->id }}</td>
                                <td class="fw-bold">{{ $question->name }}</td>
                                <td class="text-muted">{{ $question->email }}</td>
                                <td>{{ Str::limit($question->subject, 40) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border border-secondary">
                                        {{ $question->page_name }} <span class="text-muted">({{ $question->page_type }})</span>
                                    </span>
                                </td>
                                <td>
                                    @if($question->gdpr_consent)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success" data-bs-toggle="tooltip" title="Consenti v{{ $question->privacy_policy_version ?? '1.0' }} le {{ $question->consent_given_at?->format('d/m/Y H:i') ?? $question->created_at->format('d/m/Y H:i') }}">
                                            <i class='bx bx-check-shield'></i> v{{ $question->privacy_policy_version ?? '1.0' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary" data-bs-toggle="tooltip" title="Donnée antérieure aux formulaires RGPD">
                                            <i class='bx bx-minus-circle'></i> Ancien
                                        </span>
                                    @endif
                                </td>
                                <td class="text-nowrap text-secondary">
                                    {{ $question->created_at->format('d/m/Y') }}<br><small>{{ $question->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.questions.show', $question->id) }}"
                                            class="btn-action btn-action-view" title="Voir" data-bs-toggle="tooltip">
                                            <i class='bx bxs-show fs-6'></i>
                                        </a>
                                        <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">
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
                                    <i class='bx bx-search fs-1 d-block mb-2'></i>
                                    Aucune question trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
@endsection