## Cite this page
{{ $markdownCanonical ?? url()->current() }}

## Related official sources
- [Campus France](https://www.campusfrance.org/)
- [France-Visas](https://france-visas.gouv.fr/)
- [ANEF / Administration numérique des étrangers](https://administration-etrangers-en-france.interieur.gouv.fr/)
- [Service-Public.fr](https://www.service-public.fr/)

## Next action
- [Book a consultation]({{ route('consult', ['locale' => $markdownLocale ?? app()->getLocale()]) }})
- [Contact {{ $markdownOrgName ?? config('seo.organization.name') }}]({{ route('contact', ['locale' => $markdownLocale ?? app()->getLocale()]) }})

---
**Organization**: {{ $markdownOrgName ?? config('seo.organization.name') }} ({{ config('seo.organization.legal_name') }})
**Machine-Readable Summary**: [llms.txt]({{ url('/llms.txt') }}) | **Agent Index**: [agents-index.json]({{ url('/.well-known/agents-index.json') }})
