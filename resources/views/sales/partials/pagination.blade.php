
@if ($paginator->hasPages())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4">
    {{-- Entries information --}}
    <div class="text-sm text-gray-600" id="entriesInfo">
        {{ $entries_info ?? "Showing {$paginator->firstItem()} to {$paginator->lastItem()} of {$paginator->total()} entries" }}
    </div>

    {{-- Pagination links --}}
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed">Previous</span>
                </li>
            @else
                <li>
                    <a href="#"
                       data-page="{{ $paginator->currentPage() - 1 }}"
                       class="px-3 py-1 rounded-md text-gray-700 hover:bg-gray-100 sales-pagination-link">Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                // Pastikan $elements tersedia, jika tidak ambil dari paginator
                $elements = $elements ?? $paginator->links()->elements;
                $window = 1; // Jumlah halaman yang ditampilkan di setiap sisi halaman aktif
            @endphp

            {{-- First Page Link --}}
            @if ($paginator->currentPage() > $window + 1)
                <li>
                    <a href="#" data-page="1" class="px-3 py-1 rounded-md text-gray-700 hover:bg-gray-100 sales-pagination-link">1</a>
                </li>
                @if ($paginator->currentPage() > $window + 2)
                    <li aria-disabled="true">
                        <span class="px-3 py-1 rounded-md text-gray-400">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="px-3 py-1 rounded-md text-gray-400">...</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page >= $paginator->currentPage() - $window && $page <= $paginator->currentPage() + $window)
                            @if ($page == $paginator->currentPage())
                                <li aria-current="page">
                                    <span class="px-3 py-1 rounded-md bg-blue-500 text-white">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="#"
                                       data-page="{{ $page }}"
                                       class="px-3 py-1 rounded-md text-gray-700 hover:bg-gray-100 sales-pagination-link">{{ $page }}</a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Last Page Link --}}
            @if ($paginator->currentPage() < $paginator->lastPage() - $window)
                @if ($paginator->currentPage() < $paginator->lastPage() - $window - 1)
                    <li aria-disabled="true">
                        <span class="px-3 py-1 rounded-md text-gray-400">...</span>
                    </li>
                @endif
                <li>
                    <a href="#"
                       data-page="{{ $paginator->lastPage() }}"
                       class="px-3 py-1 rounded-md text-gray-700 hover:bg-gray-100 sales-pagination-link">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="#"
                       data-page="{{ $paginator->currentPage() + 1 }}"
                       class="px-3 py-1 rounded-md text-gray-700 hover:bg-gray-100 sales-pagination-link">Next</a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed">Next</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif
