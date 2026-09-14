@props([
    'title' => null,
    'subtitle' => null,
    'items' => [],
    'id' => 'faq-accordion',
    'inline' => false,
])

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
@endphp

@if($inline)
    <div class="faq-container-inline my-5">
        @if($title || $subtitle)
            <div class="section-title text-center mb-4">
                @if($subtitle)
                    <span class="text-muted small d-block mb-1">{{ $subtitle }}</span>
                @endif
                @if($title)
                    <h3 class="h3 fw-bold">{{ $title }}</h3>
                @endif
            </div>
        @endif

        <div class="faq-accordion" id="{{ $id }}">
            @foreach($items as $index => $item)
                <div class="faq-item mb-4">
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden transition-all">
                        <h3 class="accordion-header mb-0" id="heading-{{ $id }}-{{ $index }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} d-flex align-items-center justify-content-between w-100 text-start border-0 bg-white p-4" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse-{{ $id }}-{{ $index }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse-{{ $id }}-{{ $index }}">
                                <span class="faq-question fw-bold">
                                    <i class='bx bx-help-circle me-2 text-primary fs-5'></i>
                                    {{ $item['question'] }}
                                </span>
                                <span class="faq-icon ms-3 flex-shrink-0">
                                    <i class='bx bx-chevron-down fs-4 transition-transform'></i>
                                </span>
                            </button>
                        </h3>
                        <div id="collapse-{{ $id }}-{{ $index }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading-{{ $id }}-{{ $index }}" 
                             data-bs-parent="#{{ $id }}">
                            <div class="accordion-body bg-light p-4 border-top">
                                <div class="faq-answer text-muted">
                                    {!! $item['answer'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <section class="faq-area pt-100 pb-70">
        <div class="container">
            @if($title || $subtitle)
                <div class="section-title text-center">
                    @if($subtitle)
                        <span>{{ $subtitle }}</span>
                    @endif
                    @if($title)
                        <h2>{{ $title }}</h2>
                    @endif
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="faq-accordion" id="{{ $id }}">
                        @foreach($items as $index => $item)
                            <div class="faq-item mb-4">
                                <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden transition-all">
                                    <h3 class="accordion-header mb-0" id="heading-{{ $id }}-{{ $index }}">
                                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} d-flex align-items-center justify-content-between w-100 text-start border-0 bg-white p-4" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse-{{ $id }}-{{ $index }}" 
                                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapse-{{ $id }}-{{ $index }}">
                                            <span class="faq-question fw-bold">
                                                <i class='bx bx-help-circle me-2 text-primary fs-5'></i>
                                                {{ $item['question'] }}
                                            </span>
                                            <span class="faq-icon ms-3 flex-shrink-0">
                                                <i class='bx bx-chevron-down fs-4 transition-transform'></i>
                                            </span>
                                        </button>
                                    </h3>
                                    <div id="collapse-{{ $id }}-{{ $index }}" 
                                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                         aria-labelledby="heading-{{ $id }}-{{ $index }}" 
                                         data-bs-parent="#{{ $id }}">
                                        <div class="accordion-body bg-light p-4 border-top">
                                            <div class="faq-answer text-muted">
                                                {!! $item['answer'] !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@push('json')
    @php
        $faqSchema = new \App\Services\StructuredData\FAQSchema();
        foreach($items as $item) {
            $faqSchema->addQuestion($item['question'], strip_tags($item['answer']));
        }
    @endphp
    <x-seo.structured-data :schema="$faqSchema" />
@endpush

<style>
    /* FAQ Section Styling */
    .faq-area {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .faq-accordion .accordion-item {
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .faq-accordion .accordion-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .faq-accordion .accordion-button {
        font-size: 1.05rem;
        color: #2c3e50;
        transition: all 0.3s ease;
        box-shadow: none !important;
        position: relative;
    }

    .faq-accordion .accordion-button:hover {
        color: #ff5d22;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: #fff;
        color: #ff5d22;
    }

    .faq-accordion .accordion-button:not(.collapsed) .faq-icon i {
        transform: rotate(180deg);
        color: #ff5d22;
    }

    .faq-accordion .accordion-button .faq-icon i {
        transition: all 0.3s ease;
        color: #6c757d;
    }

    .faq-accordion .accordion-button::after {
        display: none;
    }

    .faq-accordion .accordion-body {
        background: #f8f9fa;
        border-top: 2px solid #e9ecef;
    }

    .faq-answer {
        line-height: 1.8;
        font-size: 0.95rem;
    }

    .faq-answer p:last-child {
        margin-bottom: 0;
    }

    /* RTL Support */
    [dir="rtl"] .faq-accordion .accordion-button .bx-help-circle {
        margin-right: 0;
        margin-left: 0.5rem;
    }

    [dir="rtl"] .faq-accordion .accordion-button .faq-icon {
        margin-left: 0;
        margin-right: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .faq-accordion .accordion-button {
            font-size: 0.95rem;
            padding: 1rem !important;
        }

        .faq-accordion .accordion-body {
            padding: 1rem !important;
        }
    }

    /* Animation for smooth expand/collapse */
    .accordion-collapse {
        transition: height 0.35s ease;
    }
</style>
