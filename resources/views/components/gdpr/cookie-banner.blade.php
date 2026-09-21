{{-- GDPR Cookie Banner Component
     Shows a bottom-fixed banner until the user makes a choice.
     Respects existing `gdpr_consent` cookie — hidden if already set.
     Submits choice via HTML form POST to /gdpr/consent (no JS required).
     Alpine.js is used only for the show/hide animation (progressive enhancement).
--}}
@php
    $locale         = app()->getLocale();
    $isRtl          = $locale === 'fa';
    $consentCookie  = request()->cookie('gdpr_consent');
    
    $parts          = explode(':', $consentCookie ?? '');
    $choice         = $parts[0] ?? null;
    $storedVersion  = $parts[1] ?? null;
    $currentVersion = config('gdpr.privacy_policy_version');

    $bannerHidden   = in_array($choice, ['accepted', 'rejected'], true) && $storedVersion === $currentVersion;
    $policyUpdated  = in_array($choice, ['accepted', 'rejected'], true) && $storedVersion !== null && $storedVersion !== $currentVersion;

    $privacyUrl     = route('privacy', ['locale' => $locale]);
@endphp

@unless($bannerHidden)
<div
    id="gdpr-cookie-banner"
    role="dialog"
    aria-modal="true"
    aria-label="{{ $isRtl ? 'اطلاعیه کوکی' : ($locale === 'fr' ? 'Avis sur les cookies' : 'Cookie notice') }}"
    dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
    style="
        position: fixed;
        bottom: 0;
        {{ $isRtl ? 'right' : 'left' }}: 0;
        width: 100%;
        z-index: 99999;
        background: #1a1a2e;
        border-top: 3px solid #28a745;
        padding: 18px 24px;
        box-shadow: 0 -8px 32px rgba(0,0,0,0.35);
    "
>
    <div class="container">
        <div class="row align-items-center gy-3">

            {{-- Text --}}
            <div class="col-lg-8 col-md-7">
                <div class="d-flex align-items-start gap-3">
                    <div style="
                        width: 40px; height: 40px; flex-shrink: 0;
                        background: rgba(40,167,69,0.2);
                        border-radius: 10px;
                        display: flex; align-items: center; justify-content: center;
                        color: #5cb85c; font-size: 1.3rem;
                    ">
                        <i class="bx bx-cookie"></i>
                    </div>
                    <div>
                        @if($policyUpdated)
                            <div class="alert alert-warning py-1 px-2 mb-2 d-inline-block" style="font-size: 0.85rem; border-radius: 4px;">
                                @if($locale === 'fa')
                                    سیاست حریم خصوصی ما به‌روزرسانی شده است. لطفاً انتخاب خود را دوباره تأیید کنید.
                                @elseif($locale === 'fr')
                                    Notre politique de confidentialité a été mise à jour. Veuillez reconfirmer votre choix.
                                @else
                                    Our privacy policy has been updated. Please review and re-confirm your choice.
                                @endif
                            </div>
                        @endif
                        <p class="mb-0 text-white fw-semibold" style="font-size:0.95rem;">
                            @if($locale === 'fa')
                                ما از کوکی‌ها برای بهبود تجربه شما و (با رضایت شما) تحلیل رفتار بازدیدکنندگان استفاده می‌کنیم.
                            @elseif($locale === 'fr')
                                Nous utilisons des cookies pour améliorer votre expérience et, avec votre consentement, analyser le comportement des visiteurs.
                            @else
                                We use cookies to improve your experience and, with your consent, to analyse visitor behaviour.
                            @endif
                        </p>
                        <p class="mb-0 mt-1" style="font-size:0.82rem; color:rgba(255,255,255,0.6);">
                            @if($locale === 'fa')
                                برای اطلاعات بیشتر <a href="{{ $privacyUrl }}" style="color:#5cb85c;">سیاست حریم خصوصی</a> ما را مطالعه کنید.
                            @elseif($locale === 'fr')
                                Pour en savoir plus, lisez notre <a href="{{ $privacyUrl }}" style="color:#5cb85c;">politique de confidentialité</a>.
                            @else
                                For more information, read our <a href="{{ $privacyUrl }}" style="color:#5cb85c;">privacy policy</a>.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="col-lg-4 col-md-5">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap">

                    {{-- Accept --}}
                    <form method="POST" action="{{ route('gdpr.consent') }}" style="margin:0;">
                        @csrf
                        <input type="hidden" name="consent" value="accepted">
                        <button type="submit"
                            style="
                                background: linear-gradient(135deg,#28a745,#20c997);
                                color:#fff; border:none; border-radius:50px;
                                padding: 10px 22px; font-weight:700; font-size:0.875rem;
                                cursor:pointer; white-space:nowrap;
                                box-shadow:0 4px 14px rgba(40,167,69,0.4);
                            "
                        >
                            <i class="bx bx-check me-1"></i>
                            @if($locale === 'fa') قبول کردن
                            @elseif($locale === 'fr') Tout accepter
                            @else Accept all
                            @endif
                        </button>
                    </form>

                    {{-- Reject --}}
                    <form method="POST" action="{{ route('gdpr.consent') }}" style="margin:0;">
                        @csrf
                        <input type="hidden" name="consent" value="rejected">
                        <button type="submit"
                            style="
                                background: transparent;
                                color: rgba(255,255,255,0.75);
                                border: 1px solid rgba(255,255,255,0.25);
                                border-radius: 50px;
                                padding: 10px 20px; font-weight:600; font-size:0.875rem;
                                cursor: pointer; white-space:nowrap;
                            "
                        >
                            <i class="bx bx-x me-1"></i>
                            @if($locale === 'fa') رد کردن
                            @elseif($locale === 'fr') Refuser
                            @else Reject
                            @endif
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endunless
