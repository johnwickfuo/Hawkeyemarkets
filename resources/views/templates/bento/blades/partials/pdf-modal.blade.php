{{-- PDF Preview Modal --}}
<div id="pdf-preview-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 lg:p-8">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="pdf-modal-overlay"></div>
    <div
        class="relative w-full max-w-5xl h-full max-h-[90vh] bg-[#0B0F17] border border-white/10 rounded-[2rem] overflow-hidden flex flex-col shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b border-white/5 bg-[#0B0F17]">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
                <span id="pdf-modal-title" class="text-sm md:text-base">{{ __('Document Preview') }}</span>
            </h3>
            <button id="close-pdf-modal"
                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex-1 bg-[#1A1D24] relative">
            {{-- Document Loader --}}
            <div id="pdf-loader"
                class="absolute inset-0 flex flex-col items-center justify-center bg-[#0B0F17] z-10 transition-opacity duration-300">
                <div class="relative w-16 h-16 mb-6">
                    <div class="absolute inset-0 border-2 border-white/5 rounded-full"></div>
                    <div
                        class="absolute inset-0 border-2 border-transparent border-t-emerald-500 rounded-full animate-spin">
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-500/50 animate-pulse" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-emerald-500/70 animate-pulse">
                    {{ __('Decrypting Document') }}</p>
            </div>
            <iframe id="pdf-iframe" src=""
                class="w-full h-full border-none opacity-0 transition-opacity duration-500"></iframe>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        {{-- PDF Preview Logic --}}
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('pdf-preview-modal');
            if (!modal) return;

            const iframe = document.getElementById('pdf-iframe');
            const loader = document.getElementById('pdf-loader');
            const title = document.getElementById('pdf-modal-title');
            const closeBtn = document.getElementById('close-pdf-modal');
            const overlay = document.getElementById('pdf-modal-overlay');
            const triggers = document.querySelectorAll('.pdf-preview-link');

            function openModal(url, docName) {
                if (loader) loader.classList.remove('hidden', 'opacity-0');
                if (iframe) {
                    iframe.classList.add('opacity-0');
                    iframe.src = url;
                }
                if (title) title.textContent = docName;
                if (modal) modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Handle load completion
                if (iframe) {
                    iframe.onload = function() {
                        if (loader) loader.classList.add('opacity-0');
                        setTimeout(() => {
                            if (loader) loader.classList.add('hidden');
                            iframe.classList.remove('opacity-0');
                        }, 300);
                    };
                }
            }

            function closeModal() {
                if (modal) modal.classList.add('hidden');
                if (iframe) {
                    iframe.src = '';
                    iframe.classList.add('opacity-0');
                }
                document.body.style.overflow = '';
            }

            triggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    // Find document title: look for .font-bold or .pdf-title or h3
                    const titleEl = this.querySelector('.font-bold') || this.querySelector(
                        '.pdf-title') || this.querySelector('h3');
                    const name = titleEl ? titleEl.textContent : "{{ __('Document Preview') }}";
                    openModal(url, name);
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (overlay) overlay.addEventListener('click', closeModal);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        });
    </script>
@endpush
