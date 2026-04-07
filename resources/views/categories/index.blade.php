<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Struktur Folder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold border-b pb-2 mb-4">Struktur Folder Saat Ini</h3>
                        
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <ul class="space-y-2">
                                @foreach($categories as $main)
                                    <li class="font-semibold text-gray-800 flex justify-between items-center bg-white p-2 border rounded">
                                        <span>📁 {{ $main->name }}</span>
                                        <form action="{{ route('categories.destroy', $main->id) }}" method="POST" onsubmit="return confirm('Hapus folder utama ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold">Hapus</button>
                                        </form>
                                    </li>
                                    
                                    @if($main->children->count() > 0)
                                        <ul class="pl-8 mt-1 space-y-1">
                                            @foreach($main->children as $child)
                                                <li class="text-gray-700 flex justify-between items-center border-b pb-1">
                                                    <span>↳ 📂 {{ $child->name }}</span>
                                                    <form action="{{ route('categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Hapus sub-folder ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                                                    </form>
                                                </li>

                                                @if($child->children->count() > 0)
                                                    <ul class="pl-8 mt-1 space-y-1">
                                                        @foreach($child->children as $subchild)
                                                            <li class="text-gray-600 text-sm flex justify-between items-center">
                                                                <span>↳ 📄 {{ $subchild->name }}</span>
                                                                <form action="{{ route('categories.destroy', $subchild->id) }}" method="POST" onsubmit="return confirm('Hapus sub-folder ini?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                                                                </form>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="mb-4"></div> @endforeach
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold border-b pb-2 mb-4">Buat Folder Baru</h3>
                        
                        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Folder Baru</label>
                                <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: CCTV Kota" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Letakkan di dalam (Opsional)</label>
                                <select name="parent_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Jadikan Folder Utama --</option>
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika ingin membuat Kategori Utama baru.</p>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition">
                                + Simpan Folder
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>