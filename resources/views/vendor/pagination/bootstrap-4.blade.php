@if ($paginator->hasPages())
    <ul class="pagination dataTables_paginate paging_simple_numbers" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="paginate_button previous disabled paginationFont paginationLi newPaginationStyleNotSelected" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="paginate_button previous disabled paginationFont " aria-hidden="true">Anterior</span>
            </li>
        @else
            <li class="paginate_button previous paginationFont paginationLi newPaginationStyleNotSelected">
                <a class="paginate_button previous paginationFont newPaginationStyle" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Anterior</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="paginate_button paginationFont disabled paginationLi" aria-disabled="true"><span class="newPaginationStyle">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="paginate_button paginationFont active paginationLi" aria-current="page"><span class="newPaginationStyle"  style="border-radius:6px;color:#333;border:1px solid #979797;background:linear-gradient(to bottom, #fff 0%, #dcdcdc 100%)">{{ $page }}</span></li>
                    @else
                        <li class="paginate_button paginationFont paginationLi newPaginationStyleNotSelected"><a class="newPaginationStyle" href="{{ $url }}" style="background-color: transparent;">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="paginate_button next paginationFont paginationLi newPaginationStyleNotSelected">
                <a class="paginate_button next paginationFont newPaginationStyle " href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Siguiente</a>
            </li>
        @else
            <li class="paginate_button next paginationFont disabled paginationLi newPaginationStyleNotSelected" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="paginate_button next paginationFont disabled  " aria-hidden="true">Siguiente</span>
            </li>
        @endif
    </ul>
@endif
