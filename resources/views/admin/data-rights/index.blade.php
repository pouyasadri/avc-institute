@extends('layouts.admin')

@section('title', 'Data Rights Requests')
@section('header', 'GDPR — Data Rights Requests')

@section('content')
    {{-- Status tabs --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center gap-3 flex-wrap py-3">
            @foreach(['pending' => 'warning', 'completed' => 'success', 'rejected' => 'danger'] as $s => $color)
            <a href="{{ route('admin.data-rights.index', ['status' => $s]) }}"
               class="btn btn-sm btn-{{ $status === $s ? $color : 'outline-secondary' }} rounded-pill px-3">
                {{ ucfirst($s) }}
                <span class="badge bg-{{ $color }} ms-1">{{ $counts[$s] }}</span>
            </a>
            @endforeach
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-0 mb-0 py-2 px-4">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Locale</th>
                            <th>Received</th>
                            <th>Deadline (30d)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            @php
                                $daysLeft = now()->diffInDays($req->created_at->addDays(30), false);
                                $urgent   = $daysLeft <= 5 && $req->isPending();
                            @endphp
                            <tr class="{{ $urgent ? 'table-warning' : '' }}">
                                <td class="text-muted small">{{ Str::limit($req->id, 8, '') }}</td>
                                <td class="fw-semibold">{{ $req->email }}</td>
                                <td>
                                    <span class="badge rounded-pill
                                        @switch($req->request_type)
                                            @case('erasure') bg-danger @break
                                            @case('access')  bg-primary @break
                                            @case('portability') bg-success @break
                                            @default bg-secondary
                                        @endswitch">
                                        {{ $req->request_type }}
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($req->locale) }}</span></td>
                                <td class="text-nowrap text-muted small">
                                    {{ $req->created_at->format('d/m/Y') }}<br>{{ $req->created_at->format('H:i') }}
                                </td>
                                <td class="text-nowrap small {{ $urgent ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ $req->created_at->addDays(30)->format('d/m/Y') }}
                                    @if($urgent)<br><span class="badge bg-danger">{{ $daysLeft }}d left</span>@endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill
                                        @switch($req->status)
                                            @case('pending')   bg-warning text-dark @break
                                            @case('completed') bg-success @break
                                            @case('rejected')  bg-danger  @break
                                        @endswitch">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.data-rights.show', $req->id) }}"
                                       class="btn-action btn-action-view" title="Review" data-bs-toggle="tooltip">
                                        <i class="bx bxs-show fs-6"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bx bx-check-shield fs-1 d-block mb-2 text-success"></i>
                                    No {{ $status }} requests.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">{{ $requests->appends(['status' => $status])->links() }}</div>
        </div>
    </div>
@endsection
