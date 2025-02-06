@if ($datas instanceof \Illuminate\Pagination\LengthAwarePaginator && $datas->hasPages())
    <nav class="my-3">
        <ul class="d-inline-flex rounded-2 overflow-hidden justify-content-center">
            {{-- Tombol Previous --}}
            <li class="btn btn-secondary rounded-0 {{ $datas->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $datas->previousPageUrl().'#produk' }}" tabindex="-1">Previous</a>
            </li>

            {{-- Tombol Next --}}
            <li class="btn btn-secondary rounded-0 {{ $datas->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $datas->nextPageUrl().'#produk' }}">Next</a>
            </li>
        </ul>
    </nav>
@endif