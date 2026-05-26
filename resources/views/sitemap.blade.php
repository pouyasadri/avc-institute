<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
@if(isset($url['image']) && is_array($url['image']))
        <image:image>
            <image:loc>{{ $url['image']['loc'] }}</image:loc>
@if(!empty($url['image']['title']))
            <image:title>{{ $url['image']['title'] }}</image:title>
@endif
        </image:image>
@endif
@if(isset($url['alternates']) && is_array($url['alternates']))
@foreach($url['alternates'] as $alternate)
        <xhtml:link rel="alternate" hreflang="{{ $alternate['hreflang'] }}" href="{{ $alternate['href'] }}" />
@endforeach
@endif
    </url>
@endforeach
</urlset>
