# {{ __('index.meta.title') }}

{{ __('index.meta.description') }}

## {{ __('index.about.title') }}
{{ __('index.about.subtitle') }}

## Our Services
For a full list of our services, please visit: [Our Services]({{ url(app()->getLocale() . '/services') }})

## Latest from the Blog
@foreach($blogs as $blog)
### {{ $blog->translate(app()->getLocale())->title }}
{{ Str::limit(strip_tags($blog->translate(app()->getLocale())->content), 150) }}
[Read more]({{ route('blog.show', ['locale' => app()->getLocale(), 'blog' => $blog->slug]) }})
@endforeach

## Contact Us
- [Book a Consultation]({{ route('consult', ['locale' => app()->getLocale()]) }})
- [Contact Form]({{ route('contact', ['locale' => app()->getLocale()]) }})

---
Machine-readable summary available at [/llms.txt]({{ url('/llms.txt') }})
