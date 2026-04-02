<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-4 md:space-y-0">
        <h2 class="text-2xl font-semibold text-gray-800">Danh sách sản phẩm</h2>
        <a wire:navigate href="{{ route('products.create') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center shrink-0">
            <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Tìm theo mã hoặc tên sản phẩm...">
            </div>
        </div>
        <div class="w-full md:w-64">
            <select wire:model.live="category_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Sản phẩm</th>
                        <th class="px-6 py-4">Mã SP</th>
                        <th class="px-6 py-4">Danh mục</th>
                        <th class="px-6 py-4">Giá bán</th>
                        <th class="px-6 py-4 text-center">Tồn hiện tại</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-md border border-gray-200 overflow-hidden">
                                        @if($product->image_path)
                                            <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-mono">{{ $product->code }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '---' }}</td>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ number_format($product->price, 0, ',', '.') }} ₫ / {{ $product->unit }}</td>
                            <td class="px-6 py-4 text-center font-semibold {{ ($product->inventory?->quantity ?? 0) < $product->min_stock ? 'text-red-500' : 'text-green-600' }}">
                                {{ number_format($product->inventory?->quantity ?? 0) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a wire:navigate href="{{ route('products.bom', $product->id) }}" class="text-orange-500 hover:text-orange-700 transition-colors" title="Định mức (BOM)">
                                        <i class="fa-solid fa-list-check"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button wire:click="delete({{ $product->id }})" wire:confirm="Bạn có chắc chắn muốn xoá sản phẩm này?" class="text-red-500 hover:text-red-700 transition-colors" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                                    <p>Không tìm thấy sản phẩm nào phù hợp</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
