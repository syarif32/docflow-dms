<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unggah Dokumen Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-500">
                <div class="p-8 text-gray-900">
                    
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 font-bold mb-1">Judul Dokumen</label>
                            <input type="text" name="title" id="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-md py-3" placeholder="Contoh: Berita Acara Instalasi Metro 2024" required>
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 font-bold mb-1">Kategori / Folder</label>
                                <select name="category_id" id="category_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-md py-3" required>
                                    <option value="">-- Pilih Lokasi Folder --</option>
                                    @foreach($categories as $main)
                                        <option value="{{ $main->id }}" class="font-bold text-gray-900 bg-gray-100">📁 {{ $main->name }}</option>
                                        @foreach($main->children as $child)
                                            <option value="{{ $child->id }}">&nbsp;&nbsp;&nbsp;↳ 📂 {{ $child->name }}</option>
                                            @foreach($child->children as $subchild)
                                                <option value="{{ $subchild->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ 📄 {{ $subchild->name }}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 font-bold mb-1">Tahun Dokumen</label>
                                <input type="number" name="year" id="year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-md py-3" value="{{ date('Y') }}" required>
                                @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 font-bold mb-1">File Dokumen <span class="text-red-500">*</span></label>
                            
                            <div id="dropzone" class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-gray-50">
                                <div class="space-y-2 text-center">
                                    
                                    <svg id="icon-default" class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <div id="file-feedback" class="hidden">
                                        <svg class="mx-auto h-12 w-12 text-green-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-bold text-indigo-700" id="file-name-display"></p>
                                        <p class="text-sm text-gray-500 mt-1">File siap diunggah!</p>
                                    </div>

                                    <div class="flex text-sm text-gray-600 justify-center mt-4">
                                        <label for="file" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-2 bg-indigo-100 hover:bg-indigo-200 transition">
                                            <span id="label-text">Pilih File dari Komputer</span>
                                            <input id="file" name="file" type="file" class="sr-only" required onchange="showFileName(this)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500" id="helper-text">Format: PDF, DOCX, XLSX (Maksimal 20MB)</p>
                                </div>
                            </div>
                            @error('file') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent shadow-lg text-lg font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-[1.02]">
                                📤 SIMPAN DAN UNGGAH DOKUMEN SEKARANG
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function showFileName(input) {
            if (input.files && input.files[0]) {
                var fileName = input.files[0].name;
                
                // Ubah UI Dropzone
                document.getElementById('dropzone').classList.remove('border-gray-300', 'bg-gray-50');
                document.getElementById('dropzone').classList.add('border-green-500', 'bg-green-50');
                
                // Sembunyikan ikon lama, munculkan nama file
                document.getElementById('icon-default').classList.add('hidden');
                document.getElementById('helper-text').classList.add('hidden');
                document.getElementById('file-feedback').classList.remove('hidden');
                document.getElementById('file-name-display').innerText = fileName;
                
                // Ubah teks tombol pilih
                document.getElementById('label-text').innerText = 'Ganti File Lain';
            }
        }
    </script>
</x-app-layout>