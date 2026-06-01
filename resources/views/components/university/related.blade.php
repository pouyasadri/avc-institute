{{-- Related Universities Component --}}
{{-- Groups universities by city/region and shows up to 4 siblings for cross-linking --}}
@props(['currentSlug' => ''])

@php
    $allUniversities = [
        // Paris & Île-de-France group
        'paris' => [
            ['slug' => 'paris-saclay-university',  'name_key' => 'paris_saclay'],
            ['slug' => 'sorbonne-paris-nord',       'name_key' => 'sorbonne_paris_nord'],
            ['slug' => 'paris-cite',                'name_key' => 'paris_cite'],
            ['slug' => 'paris-4-sorbonne',          'name_key' => 'paris_4'],
            ['slug' => 'paris-3',                   'name_key' => 'paris_3'],
            ['slug' => 'paris-2',                   'name_key' => 'paris_2'],
            ['slug' => 'pantheon-sorbonne',         'name_key' => 'pantheon_sorbonne'],
            ['slug' => 'universite-psl',             'name_key' => 'psl'],
            ['slug' => 'ip-paris',                  'name_key' => 'ip_paris'],
            ['slug' => 'sciences-po',               'name_key' => 'sciences_po'],
        ],
        // Lyon group
        'lyon' => [
            ['slug' => 'lyon-1', 'name_key' => 'lyon_1'],
            ['slug' => 'lyon-2', 'name_key' => 'lyon_2'],
            ['slug' => 'lyon-3', 'name_key' => 'lyon_3'],
        ],
        // South of France group
        'south' => [
            ['slug' => 'cote-d-azure',          'name_key' => 'nice'],
            ['slug' => 'aix-marseille-university','name_key' => 'aix_marseille'],
            ['slug' => 'universite-de-montpellier','name_key' => 'montpellier'],
            ['slug' => 'toulouse',              'name_key' => 'toulouse'],
        ],
        // Other regions
        'other' => [
            ['slug' => 'strasbourg',                 'name_key' => 'strasbourg'],
            ['slug' => 'universite-grenoble-alpes',  'name_key' => 'grenoble_alpes'],
            ['slug' => 'universite-de-bordeaux',     'name_key' => 'bordeaux'],
            ['slug' => 'universite-de-lille',        'name_key' => 'lille'],
        ],
    ];

    // Find which group the current university belongs to
    $relatedList = [];
    $foundGroup = null;
    foreach ($allUniversities as $groupKey => $unis) {
        $slugsInGroup = array_column($unis, 'slug');
        if (in_array($currentSlug, $slugsInGroup, true)) {
            $foundGroup = $groupKey;
            // Exclude current university, take up to 4
            $relatedList = array_slice(
                array_values(array_filter($unis, fn($u) => $u['slug'] !== $currentSlug)),
                0,
                4
            );
            break;
        }
    }

    // Fallback: if group has fewer than 2 results, supplement from a sibling group
    if (count($relatedList) < 2) {
        $fallbackGroup = ($foundGroup === 'paris') ? 'lyon' : 'paris';
        $relatedList = array_merge(
            $relatedList,
            array_slice($allUniversities[$fallbackGroup] ?? [], 0, 4 - count($relatedList))
        );
    }

    $currentLocale = app()->getLocale();
@endphp

@if(!empty($relatedList))
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('universities.related_universities') }}
        </h4>
        <ul class="list-unstyled mb-0">
            @foreach($relatedList as $related)
                <li class="mb-2">
                    <a href="{{ url($currentLocale . '/universities/' . $related['slug']) }}"
                       class="d-flex align-items-center text-decoration-none text-dark hover-translate-x">
                        <i class='bx bx-graduation me-2 fs-5 text-primary flex-shrink-0'></i>
                        <span class="small fw-medium">{{ __('universities.' . $related['name_key'] . '_name') }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
