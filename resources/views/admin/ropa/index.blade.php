@extends('layouts.admin')

@section('title', 'Registre des Activités de Traitement (ROPA)')
@section('header', 'Registre des Activités de Traitement — RGPD Art. 30')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <p class="text-muted mb-0">
            Document réglementaire officiel conforme aux exigences de la CNIL et à l'article 30 du RGPD.
        </p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class='bx bx-printer me-1'></i> Imprimer / Exporter PDF
        </button>
    </div>
</div>

{{-- Fiche Responsable du Traitement --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class='bx bx-building me-2'></i>Responsable du Traitement & Cadre Légal</h6>
        <span class="badge bg-success rounded-pill px-3">Conforme CNIL · 2026</span>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <span class="text-muted small d-block">Organisme</span>
                <strong class="text-dark">{{ $controller['name'] ?? 'AVC Institute' }}</strong>
                <div class="small text-secondary">{{ $controller['legal_form'] ?? '' }}</div>
                <div class="small text-secondary">{{ $controller['registration'] ?? '' }}</div>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">Coordonnées</span>
                <div class="small text-dark"><i class='bx bx-map-pin me-1 text-primary'></i> {{ $controller['address'] ?? '' }}</div>
                <div class="small text-dark"><i class='bx bx-envelope me-1 text-primary'></i> {{ $controller['email'] ?? '' }}</div>
                <div class="small text-dark"><i class='bx bx-shield-quarter me-1 text-success'></i> <strong>DPO :</strong> {{ $controller['dpo_email'] ?? '' }}</div>
            </div>
            <div class="col-12 border-top pt-3 mt-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="small text-muted">
                    <strong>Autorité de contrôle :</strong> {{ $controller['supervisory_authority'] ?? 'CNIL' }}
                </div>
                <div class="small text-muted">
                    Dernière mise à jour du registre : <span class="badge bg-light text-dark border">{{ $controller['last_updated'] ?? date('Y-m-d') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Procédure de Violation de Données (Art. 33/34) --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-warning border-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-start gap-3">
            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-3">
                <i class='bx bx-alarm-exclamation'></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-dark mb-1">Procédure d'Alerte Violation de Données (Articles 33 & 34 RGPD)</h6>
                <p class="small text-muted mb-2">
                    En cas de violation de sécurité entraînant la destruction, perte, altération ou divulgation non autorisée de données personnelles :
                </p>
                <div class="row g-2 small text-secondary">
                    <div class="col-md-4">
                        <i class='bx bx-check-circle text-warning me-1'></i> <strong>Délai légal :</strong> Notification à la CNIL sous <strong>72 heures</strong> maximum via <code>notifications.cnil.fr</code>.
                    </div>
                    <div class="col-md-4">
                        <i class='bx bx-check-circle text-warning me-1'></i> <strong>Évaluation d'impact :</strong> Gravité et risque pour les droits et libertés des personnes concernées.
                    </div>
                    <div class="col-md-4">
                        <i class='bx bx-check-circle text-warning me-1'></i> <strong>Notification usager :</strong> Obligatoire sans délai si le risque est élevé (Art. 34).
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tableau des Activités de Traitement --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class='bx bx-list-check me-2 text-primary'></i>Fiches d'Activités de Traitement ({{ count($activities) }})</h6>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-1 rounded-pill">
            7 Activités répertoriées
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 8%;">Réf.</th>
                        <th style="width: 20%;">Activité & Finalité</th>
                        <th style="width: 15%;">Base Légale</th>
                        <th style="width: 20%;">Catégories de Données</th>
                        <th style="width: 15%;">Destinataires</th>
                        <th style="width: 12%;">Durée de Rétention</th>
                        <th style="width: 10%;">Sécurité</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $act)
                        <tr>
                            <td class="fw-bold text-primary small">
                                <span class="badge bg-light text-dark border">{{ $act['code'] }}</span>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $act['name'] }}</strong>
                                <small class="text-muted fst-italic">{{ $act['name_en'] ?? '' }}</small>
                                <div class="small text-secondary mt-1">{{ $act['purpose'] }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 small text-wrap">
                                    {{ $act['legal_basis'] }}
                                </span>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small text-secondary">
                                    @foreach($act['data_categories'] as $cat)
                                        <li><i class='bx bx-caret-right text-muted'></i> {{ $cat }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small text-secondary">
                                    @foreach($act['recipients'] as $rec)
                                        <li><i class='bx bx-user-check text-success'></i> {{ $rec }}</li>
                                    @endforeach
                                </ul>
                                <small class="text-muted d-block mt-1"><strong>Transferts :</strong> {{ $act['international_transfers'] }}</small>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning px-2 py-1 small text-wrap">
                                    {{ $act['retention'] }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted d-block" style="font-size: 0.78rem;">
                                    {{ $act['security_measures'] }}
                                </small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
