@props([
    'faqs' => [],
    'withSchema' => true,
    'id' => null,
    'headingTag' => 'h3',
    'allowHtml' => true,
    'openFirst' => true,
])

@php
    $accordionId = $id ?? 'faq-' . \Illuminate\Support\Str::random(8);
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
@endphp

@if(count($faqs) > 0)
    <div class="faq-section" itemscope itemtype="https://schema.org/FAQPage">
        <div class="accordion faq-accordion" id="{{ $accordionId }}">
            @foreach($faqs as $index => $faq)
                @php
                    $question = is_array($faq) ? ($faq['question'] ?? '') : ($faq->question ?? '');
                    $answer = is_array($faq) ? ($faq['answer'] ?? '') : ($faq->answer ?? '');
                    $itemSlug = 'faq-' . \Illuminate\Support\Str::slug($question);
                    $isExpanded = $openFirst && $index === 0;
                    $cleanAnswer = $allowHtml ? $answer : nl2br(e($answer));
                @endphp
                
                <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm overflow-hidden transition-all" 
                     id="{{ $itemSlug }}"
                     itemscope 
                     itemprop="mainEntity" 
                     itemtype="https://schema.org/Question"
                     data-faq-id="{{ $index + 1 }}">
                    
                    <{{ $headingTag }} class="accordion-header mb-0" id="heading-{{ $accordionId }}-{{ $index }}">
                        <button class="accordion-button {{ $isExpanded ? '' : 'collapsed' }} fw-bold p-3 p-md-4 d-flex align-items-center justify-content-between text-start border-0 bg-white" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse-{{ $accordionId }}-{{ $index }}" 
                                aria-expanded="{{ $isExpanded ? 'true' : 'false' }}" 
                                aria-controls="collapse-{{ $accordionId }}-{{ $index }}">
                            <span itemprop="name" class="faq-question-text flex-grow-1 me-2">
                                {{ $question }}
                            </span>
                        </button>
                    </{{ $headingTag }}>

                    <div id="collapse-{{ $accordionId }}-{{ $index }}" 
                         class="accordion-collapse collapse {{ $isExpanded ? 'show' : '' }}" 
                         aria-labelledby="heading-{{ $accordionId }}-{{ $index }}" 
                         data-bs-parent="#{{ $accordionId }}"
                         itemscope 
                         itemprop="acceptedAnswer" 
                         itemtype="https://schema.org/Answer">
                        
                        <div class="accordion-body bg-light p-3 p-md-4 border-top" itemprop="text">
                            <div class="faq-answer-content text-secondary lh-lg">
                                {!! $cleanAnswer !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($withSchema)
            @push('json')
                @php
                    $faqSchema = new \App\Services\StructuredData\FAQSchema();
                    foreach($faqs as $faq) {
                        $q = is_array($faq) ? ($faq['question'] ?? '') : ($faq->question ?? '');
                        $a = is_array($faq) ? ($faq['answer'] ?? '') : ($faq->answer ?? '');
                        $faqSchema->addQuestion($q, strip_tags($a));
                    }
                @endphp
                <x-seo.structured-data :schema="$faqSchema" />
            @endpush
        @endif
    </div>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const hash = window.location.hash;
                    if (hash && hash.startsWith('#faq-')) {
                        const targetEl = document.querySelector(hash);
                        if (targetEl) {
                            const btn = targetEl.querySelector('.accordion-button');
                            const collapseEl = targetEl.querySelector('.accordion-collapse');
                            if (btn && collapseEl) {
                                if (btn.classList.contains('collapsed')) {
                                    if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                                        new bootstrap.Collapse(collapseEl, { show: true });
                                    } else {
                                        collapseEl.classList.add('show');
                                        btn.classList.remove('collapsed');
                                        btn.setAttribute('aria-expanded', 'true');
                                    }
                                }
                                setTimeout(function() {
                                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }, 150);
                            }
                        }
                    }
                });
            </script>
        @endpush
    @endonce
@endif

