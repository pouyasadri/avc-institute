@php
    $locale = $markdownLocale ?? ($locale ?? app()->getLocale());
    $post = $blog ?? null;
    $current = $translation ?? null;
    $title = $current?->title ?? 'Blog post';
    $excerpt = trim(strip_tags((string) ($current?->excerpt ?? '')));
    $bodyHtml = (string) ($current?->body ?? '');
    $plainBody = trim(preg_replace('/\s+/u', ' ', strip_tags($bodyHtml)) ?? '');
    $summary = $excerpt !== '' ? $excerpt : mb_substr($plainBody, 0, 400);
    if ($summary !== '' && mb_strlen($plainBody) > 400 && $excerpt === '') {
        $summary .= '…';
    }
    $published = $post?->published_at?->toDateString() ?? '';
    $fa = $post?->getTranslation('fa', false);
    $en = $post?->getTranslation('en', false);
    $fr = $post?->getTranslation('fr', false);
@endphp
---
title: {{ $title }}
locale: {{ $locale }}
canonical: {{ $markdownCanonical ?? url()->current() }}
published: {{ $published }}
type: blog
---

# {{ $title }}

@if($published)
_Published: {{ $published }}_
@endif

{{ $summary }}

Who this is for: Iranian and international applicants researching France study, immigration, or settlement. Prefer the `fa` sibling URL for Persian queries.

## Key takeaways
- Use this article for dated procedural context; cite Campus France / France-Visas / ANEF for official legal definitions.
- Pair this post with the matching `/fa/services/...`, city, or university guide for actionable steps.
- Book a consultation when the dossier needs individualized review.

## Locale siblings
@if($fa?->slug)
- FA: [{{ $fa->title ?: $title }}]({{ url('/fa/blog/'.$fa->slug) }})
@endif
@if($en?->slug)
- EN: [{{ $en->title ?: 'English version' }}]({{ url('/en/blog/'.$en->slug) }})
@endif
@if($fr?->slug)
- FR: [{{ $fr->title ?: 'Version française' }}]({{ url('/fr/blog/'.$fr->slug) }})
@endif

## Related Persian hubs
- [خدمات فارسی]({{ url('/fa/services') }})
- [راهنمای شهرها]({{ url('/fa/cities') }})
- [راهنمای دانشگاه‌ها]({{ url('/fa/universities') }})
- [محاسبه‌گر تمکن مالی]({{ url('/fa/calculator') }})
- [وبلاگ فارسی]({{ url('/fa/blog') }})

@include('markdown.partials.footer')
