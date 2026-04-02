<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Danh mục sản phẩm</h2>
        <a wire:navigate href="{{ route('categories.create') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Thêm danh mục
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Tên danh mục</th>
                        <th class="px-6 py-4">Mô tả</th>
                        <th class="px-6 py-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">#{{ $category->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ Str::limit($category->description, 50) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a wire:navigate href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button wire:click="delete({{ $category->id }})" wire:confirm="Bạn có chắc chắn muốn xoá danh mục này?" class="text-red-500 hover:text-red-700 transition-colors" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                    <p>Chưa có danh mục nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
