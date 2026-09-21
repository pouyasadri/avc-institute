@extends('layouts.main')

@section('title', __('privacy.withdraw.title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">{{ __('privacy.withdraw.title') }}</h1>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p>{{ __('privacy.withdraw.description') }}</p>

                    <form method="POST" action="{{ route('consent.withdraw.submit', app()->getLocale()) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            {{ __('privacy.withdraw.button') }}
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-muted small">
                        {{ __('privacy.withdraw.erasure_note') }}
                        <a href="{{ route('data-rights', app()->getLocale()) }}">{{ __('privacy.withdraw.erasure_link') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
