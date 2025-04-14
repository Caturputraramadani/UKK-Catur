<table class="text-left w-full whitespace-nowrap text-sm text-gray-500">
    <thead>
        <tr class="text-sm">
            <th class="p-4 font-semibold">#</th>
            <th class="p-4 font-semibold">CUSTOMER NAME</th>
            <th class="p-4 font-semibold">SALE DATE</th>
            <th class="p-4 font-semibold">TOTAL PRICE</th>
            <th class="p-4 font-semibold">MADE BY</th>
            <th class="p-4 font-semibold">ACTION</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
            <tr>
                <td class="p-4">
                    <h3 class="font-medium">{{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}</h3>
                </td>
                <td class="p-4">
                    <h3 class="font-medium">{{ $sale->member ? $sale->member->name : 'Non-Member' }}</h3>
                </td>
                <td class="p-4">
                    <h3 class="font-medium">{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</h3>
                </td>
                <td class="p-4">
                    <h3 class="font-medium">Rp {{ number_format($sale->sub_total, 0, ',', '.') }}</h3>
                </td>
                <td class="p-4">
                    <h3 class="font-medium text-teal-500  capitalize">{{ $sale->user->name }}</h3>
                </td>
                <td class="p-4">
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right] sm:[--trigger:hover]">
                        <a class="relative hs-dropdown-toggle cursor-pointer align-middle rounded-full">
                            <i class="ti ti-dots-vertical text-2xl text-gray-400"></i>
                        </a>
                        <div
                            class="card hs-dropdown-menu transition-[opacity,margin] rounded-md duration hs-dropdown-open:opacity-100 opacity-0 mt-2 min-w-max w-[150px] hidden z-[12]">
                            <div class="card-body p-0 py-2">
                                <!-- Tombol Lihat -->
                                <a href="javascript:void(0);" onclick="showSalesDetail({{ $sale->id }})"
                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-600">
                                    <i class="fas fa-eye"></i>
                                    <p class="text-sm">Detail</p>
                                </a>
                                <!-- Tombol Unduh Bukti -->
                                <a href="{{ route('sales.exportPdf', $sale->id) }}"
                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-600">
                                    <i class="fas fa-download"></i>
                                    <p class="text-sm">Download</p>
                                </a>

                                <!-- Tombol Hapus -->
                                {{-- <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex gap-2 items-center font-medium px-4 py-2.5 w-full text-left hover:bg-gray-200 text-gray-600">
                                        <i class="fas fa-trash"></i>
                                        <p class="text-sm">Delete</p>
                                    </button>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">Sales not found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
