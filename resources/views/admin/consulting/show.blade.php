@extends('layouts.admin')

@section('title', 'Détails Consultation')

@section('header', 'Détails de la Demande #' . $consulting->id)

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{ route('admin.consulting.index') }}" class="btn btn-secondary btn-sm">
                <i class='bx bx-arrow-back'></i> Retour
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nom:</strong>
                    <p>{{ $consulting->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong>
                    <p><a href="mailto:{{ $consulting->email }}">{{ $consulting->email }}</a></p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Téléphone:</strong>
                    <p>{{ $consulting->phone_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Service:</strong>
                    <p>{{ $consulting->service }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Date:</strong>
                    <p>{{ $consulting->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Consentement RGPD:</strong>
                    <p>
                        @if($consulting->gdpr_consent)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                <i class='bx bx-check-shield'></i> Accordé (v{{ $consulting->privacy_policy_version ?? '1.0' }})
                            </span>
                            <br>
                            <small class="text-muted">Horodatage: {{ $consulting->consent_given_at?->format('d/m/Y H:i:s') ?? $consulting->created_at->format('d/m/Y H:i:s') }}</small>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">
                                <i class='bx bx-minus-circle'></i> Non spécifié (antérieur au RGPD)
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-12 mb-3">
                    <strong>Détails:</strong>
                    <div class="p-3 bg-light border rounded">
                        {!! nl2br(e($consulting->details)) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.consulting.destroy', $consulting->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class='bx bxs-trash'></i> Supprimer
                </button>
            </form>
        </div>
    </div>
@endsection