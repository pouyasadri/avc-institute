@extends('layouts.admin')

@section('title', 'Consultations')

@section('header', 'Demandes de Consultation')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">ID</th>
                            <th scope="col" style="width: 20%;">Nom</th>
                            <th scope="col" style="width: 20%;">Email</th>
                            <th scope="col" style="width: 20%;">Service</th>
                            <th scope="col" style="width: 12%;">RGPD</th>
                            <th scope="col" style="width: 13%;">Date</th>
                            <th scope="col" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultations as $consultation)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $consultation->id }}</td>
                                <td class="fw-bold">{{ $consultation->name }}</td>
                                <td class="text-muted">{{ $consultation->email }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                        {{ $consultation->service }}
                                    </span>
                                </td>
                                <td>
                                    @if($consultation->gdpr_consent)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success" data-bs-toggle="tooltip" title="Consenti v{{ $consultation->privacy_policy_version ?? '1.0' }} le {{ $consultation->consent_given_at?->format('d/m/Y H:i') ?? $consultation->created_at->format('d/m/Y H:i') }}">
                                            <i class='bx bx-check-shield'></i> v{{ $consultation->privacy_policy_version ?? '1.0' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary" data-bs-toggle="tooltip" title="Donnée antérieure aux formulaires RGPD">
                                            <i class='bx bx-minus-circle'></i> Ancien
                                        </span>
                                    @endif
                                </td>
                                <td class="text-nowrap text-secondary">
                                    {{ $consultation->created_at->format('d/m/Y') }}<br><small>{{ $consultation->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.consulting.show', $consultation->id) }}"
                                            class="btn-action btn-action-view" title="Voir" data-bs-toggle="tooltip">
                                            <i class='bx bxs-show fs-6'></i>
                                        </a>
                                        <form action="{{ route('admin.consulting.destroy', $consultation->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class='bx bx-file-blank fs-1 d-block mb-2'></i>
                                    Aucune demande trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $consultations->links() }}
            </div>
        </div>
    </div>
@endsection