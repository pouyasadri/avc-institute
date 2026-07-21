@props(['toc', 'title' => __('blog/show.table_of_contents')])

@if(count($toc) > 0)
    <nav class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0" aria-label="Table of contents">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ $title }}
        </h4>
        <ul class="toc-list ps-0 mb-0 list-unstyled" style="font-size: 0.95rem;">
            @foreach($toc as $item)
                <li class="mb-2 {{ $item['level'] === 3 ? 'ms-3' : '' }}">
                    <a href="#{{ $item['id'] }}" class="text-decoration-none text-muted hover-lift d-flex align-items-start gap-2" style="transition: all 0.3s ease;">
                        <i class="bx bx-chevron-right text-primary mt-1"></i>
                        <span>{{ $item['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    <style>
        .toc-list a:hover {
            color: var(--bs-primary) !important;
            transform: translateX(5px);
        }
    </style>
@endif
