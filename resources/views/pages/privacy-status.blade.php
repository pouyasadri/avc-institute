@extends('layouts.main')

@section('title', __('privacy.status.title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h4 mb-0">{{ __('privacy.status.title') }}</h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>{{ __('privacy.status.policy_version') }}</th>
                                <td>
                                    <meta name="gdpr-policy-version" content="{{ $policyVersion }}">
                                    {{ $policyVersion }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('privacy.status.retention_days') }}</th>
                                <td>{{ $retentionDays }} {{ __('privacy.status.days') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('privacy.status.tech_retention') }}</th>
                                <td>{{ $techRetention }} {{ __('privacy.status.days') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('privacy.status.last_purge') }}</th>
                                <td>
                                    @if($lastPurge)
                                        <meta name="gdpr-last-purge" content="{{ $lastPurge }}">
                                        {{ \Carbon\Carbon::parse($lastPurge)->locale(app()->getLocale())->translatedFormat('Y-m-d H:i') }}
                                    @else
                                        <span class="text-muted">{{ __('privacy.status.not_run_yet') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('privacy.status.dpo_email') }}</th>
                                <td><a href="mailto:{{ $dpoEmail }}">{{ $dpoEmail }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('privacy.status.auth_url') }}</th>
                                <td><a href="{{ $authUrl }}" target="_blank" rel="noopener noreferrer">{{ $authUrl }}</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
