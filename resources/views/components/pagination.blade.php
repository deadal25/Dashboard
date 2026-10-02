@if ($paginator->hasPages())
    <nav class="custom-pagination" role="navigation" aria-label="Navigasi Halaman">
        <div class="pagination-info">
            Menampilkan <span class="font-bold">{{ $paginator->firstItem() }}</span> s/d <span class="font-bold">{{ $paginator->lastItem() }}</span> dari <span class="font-bold">{{ $paginator->total() }}</span> karyawan
        </div>

        <div class="pagination-buttons">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true" title="Halaman Pertama">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" title="Halaman Sebelumnya">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Nomor Halaman --}}
            <div class="pagination-numbers">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-ellipsis">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page-item active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page-item">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" title="Halaman Selanjutnya">
                    <span>Selanjutnya</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true" title="Halaman Terakhir">
                    <span>Selanjutnya</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>
    </nav>
@elseif($paginator->total() > 0)
    <div class="custom-pagination" style="justify-content: flex-start;">
        <div class="pagination-info">
            Menampilkan seluruh <span class="font-bold">{{ $paginator->total() }}</span> karyawan
        </div>
    </div>
@endif
