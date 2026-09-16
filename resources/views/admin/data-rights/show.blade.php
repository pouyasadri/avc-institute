@extends('layouts.admin')

@section('title', 'Data Rights Request — ' . $dataRight->email)
@section('header', 'GDPR Request Detail')

@section('content')
    <div class="row g-4">
        {{-- Left: Request details + update form --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-dark text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bx bx-id-card me-2"></i>Request Details</h6>
                        <span class="badge
                            @switch($dataRight->status)
                                @case('pending')   bg-warning text-dark @break
                                @case('completed') bg-success @break
                                @case('rejected')  bg-danger  @break
                            @endswitch rounded-pill">
                            {{ $dataRight->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted small">ID</dt>
                        <dd class="col-7 small"><code>{{ $dataRight->id }}</code></dd>

                        <dt class="col-5 text-muted small">Email</dt>
                        <dd class="col-7 fw-semibold">{{ $dataRight->email }}</dd>

                        <dt class="col-5 text-muted small">Type</dt>
                        <dd class="col-7">
                            <span class="badge bg-primary">{{ $dataRight->request_type }}</span>
                            <div class="small text-muted">{{ \App\Models\DataRightsRequest::REQUEST_TYPE_LABELS[$dataRight->request_type] ?? '' }}</div>
                        </dd>

                        <dt class="col-5 text-muted small">Received</dt>
                        <dd class="col-7 small">{{ $dataRight->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-5 text-muted small">Deadline</dt>
                        <dd class="col-7 small {{ now()->gt($dataRight->created_at->addDays(30)) ? 'text-danger fw-bold' : '' }}">
                            {{ $dataRight->created_at->addDays(30)->format('d/m/Y') }}
                            (GDPR Art. 12)
                        </dd>

                        <dt class="col-5 text-muted small">Locale</dt>
                        <dd class="col-7 small">{{ strtoupper($dataRight->locale) }}</dd>

                        @if($dataRight->notes_requester)
                        <dt class="col-5 text-muted small">Requester notes</dt>
                        <dd class="col-7 small">{{ $dataRight->notes_requester }}</dd>
                        @endif

                        @if($dataRight->notes_admin)
                        <dt class="col-5 text-muted small">Admin notes</dt>
                        <dd class="col-7 small">{{ $dataRight->notes_admin }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Update Status Form --}}
            @if($dataRight->isPending())
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0"><i class="bx bx-check-circle me-2 text-success"></i>Update Status</h6>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('admin.data-rights.update', $dataRight->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">New Status</label>
                            <div class="d-flex gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="s_completed" value="completed">
                                    <label class="form-check-label text-success" for="s_completed">Completed</label>
                                </div>
                                <div class="form-check ms-3">
                                    <input class="form-check-input" type="radio" name="status" id="s_rejected" value="rejected">
                                    <label class="form-check-label text-danger" for="s_rejected">Rejected</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes_admin" class="form-label small fw-semibold">Admin Notes</label>
                            <textarea name="notes_admin" id="notes_admin" rows="3" class="form-control rounded-3 small">{{ old('notes_admin', $dataRight->notes_admin) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">
                            <i class="bx bx-save me-1"></i> Save
                        </button>
                    </form>
                </div>
            </div>

            {{-- Erasure Action (Art. 17) --}}
            @if($dataRight->request_type === 'erasure')
            <div class="card border-danger border-0 shadow-sm rounded-4" style="border-left:4px solid #dc3545!important">
                <div class="card-body p-4">
                    <h6 class="text-danger mb-1"><i class="bx bx-trash me-1"></i>Execute Erasure (Art. 17)</h6>
                    <p class="small text-muted mb-3">This will soft-delete ALL submissions (contact, consulting, questions) for <strong>{{ $dataRight->email }}</strong>. This action cannot be undone without database intervention.</p>
                    <form action="{{ route('admin.data-rights.erase', $dataRight->id) }}" method="POST"
                          onsubmit="return confirm('Permanently erase all data for {{ $dataRight->email }}? This is irreversible.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4">
                            <i class="bx bx-trash me-1"></i> Confirm Erasure
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endif
        </div>

        {{-- Right: Matched submissions --}}
        <div class="col-lg-7">
            {{-- Contact Submissions --}}
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-header bg-white border-bottom py-2 px-4 d-flex justify-content-between">
                    <h6 class="mb-0 small fw-semibold">Contact Submissions <span class="badge bg-primary">{{ $matchedData['contact']->count() }}</span></h6>
                </div>
                @if($matchedData['contact']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Date</th><th>Subject</th><th>Message (preview)</th></tr></thead>
                        <tbody>
                            @foreach($matchedData['contact'] as $row)
                            <tr>
                                <td class="text-nowrap text-muted small">{{ $row->created_at->format('d/m/Y') }}</td>
                                <td class="small">{{ Str::limit($row->subject, 30) }}</td>
                                <td class="text-muted small">{{ Str::limit($row->message, 50) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="card-body text-muted small py-2 px-4">No records.</div>
                @endif
            </div>

            {{-- Consulting Submissions --}}
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-header bg-white border-bottom py-2 px-4 d-flex justify-content-between">
                    <h6 class="mb-0 small fw-semibold">Consulting Submissions <span class="badge bg-info">{{ $matchedData['consulting']->count() }}</span></h6>
                </div>
                @if($matchedData['consulting']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Date</th><th>Service</th><th>Details (preview)</th></tr></thead>
                        <tbody>
                            @foreach($matchedData['consulting'] as $row)
                            <tr>
                                <td class="text-nowrap text-muted small">{{ $row->created_at->format('d/m/Y') }}</td>
                                <td class="small">{{ $row->service }}</td>
                                <td class="text-muted small">{{ Str::limit($row->details, 50) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="card-body text-muted small py-2 px-4">No records.</div>
                @endif
            </div>

            {{-- Question Submissions --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-2 px-4">
                    <h6 class="mb-0 small fw-semibold">Questions <span class="badge bg-secondary">{{ $matchedData['questions']->count() }}</span></h6>
                </div>
                @if($matchedData['questions']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Date</th><th>Page</th><th>Subject</th></tr></thead>
                        <tbody>
                            @foreach($matchedData['questions'] as $row)
                            <tr>
                                <td class="text-nowrap text-muted small">{{ $row->created_at->format('d/m/Y') }}</td>
                                <td class="small">{{ $row->page_name }}</td>
                                <td class="small">{{ Str::limit($row->subject, 40) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="card-body text-muted small py-2 px-4">No records.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.data-rights.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>
@endsection
