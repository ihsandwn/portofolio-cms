@props(['file', 'title'])

<div x-data="pdfFlipper('{{ asset('storage/' . $file) }}')" class="my-8">
    <!-- Trigger Card -->
    <div class="relative group cursor-pointer bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-indigo-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10"
         @click="openViewer">
        <div class="flex items-center p-6">
            <div class="flex-shrink-0 mr-6">
                <!-- Book Icon / Mockup -->
                <div class="w-16 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg flex items-center justify-center transform group-hover:-translate-y-1 transition duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-400 transition">{{ $title ?? 'Ebook Resource' }}</h4>
                <p class="text-slate-400 text-sm mb-4">Click to read this interactive book.</p>
                <span class="inline-flex items-center text-sm font-bold text-indigo-400 uppercase tracking-wider">
                    Read Now <span class="ml-2">&rarr;</span>
                </span>
            </div>
            <!-- Background Decoration -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        </div>
    </div>

    <!-- Fullscreen Modal -->
    <div x-show="isOpen" 
         style="display: none;"
         class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-sm flex flex-col"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Toolbar -->
        <div class="flex justify-between items-center p-4 border-b border-white/10 bg-slate-900/50">
            <div class="text-white font-medium truncate">{{ $title }}</div>
            <div class="flex items-center gap-4">
                 <a href="{{ asset('storage/' . $file) }}" download class="text-slate-400 hover:text-white transition text-sm">Download PDF</a>
                <button @click="closeViewer" class="p-2 hover:bg-white/10 rounded-full text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Viewer Container -->
        <div class="flex-1 relative flex items-center justify-center overflow-hidden p-4 md:p-8">
            <!-- Loading State -->
            <div x-show="loading" class="absolute inset-0 flex flex-col items-center justify-center text-white z-10">
                <svg class="animate-spin h-10 w-10 text-indigo-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="loadingText">Loading Book...</span>
                <!-- Debug Button if stuck -->
                 <button x-show="error" @click="initBook" class="mt-4 px-4 py-2 bg-indigo-600 rounded text-sm">Retry</button>
                 <div x-show="error" class="mt-2 text-red-400 text-sm" x-text="errorMessage"></div>
            </div>

            <!-- Book Element -->
            <div id="book-container-{{ md5($file) }}" class="relative shadow-2xl">
                <!-- Pages will be injected here -->
            </div>
            
             <!-- Navigation Controls (Visible on Desktop) -->
            <button @click="prevPage" class="hidden md:block absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur transition transform hover:scale-110 z-20">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
             <button @click="nextPage" class="hidden md:block absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur transition transform hover:scale-110 z-20">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
        
         <!-- Page Info -->
        <div class="text-center p-4 text-slate-500 text-sm" x-show="!loading">
            Page <span x-text="currentPage + 1"></span> of <span x-text="totalPages"></span>
        </div>
    </div>
</div>

@once
    <!-- Load required libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://unpkg.com/page-flip/dist/js/page-flip.browser.js"></script>
    <script>
        // Set worker globally
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        document.addEventListener('alpine:init', () => {
            Alpine.data('pdfFlipper', (pdfUrl) => {
                // Non-reactive instances (Fixes Proxy #private field error)
                let pdfDocInstance = null;
                let bookInstance = null;

                return {
                    isOpen: false,
                    loading: true,
                    loadingText: 'Initializing...',
                    error: false,
                    errorMessage: '',
                    currentPage: 0,
                    totalPages: 0,
                    
                    openViewer() {
                        this.isOpen = true;
                        this.error = false;
                        document.body.style.overflow = 'hidden'; // Prevent scroll
                        
                        if (!bookInstance) {
                            this.$nextTick(() => {
                                this.initBook();
                            });
                        }
                    },
                    
                    closeViewer() {
                        this.isOpen = false;
                        document.body.style.overflow = '';
                    },

                    async initBook() {
                        try {
                            this.loading = true;
                            this.loadingText = 'Fetching Document...';
                            
                            // 1. Load PDF
                            const loadingTask = pdfjsLib.getDocument(pdfUrl);
                            pdfDocInstance = await loadingTask.promise;
                            this.totalPages = pdfDocInstance.numPages;

                            // 2. Get Layout Dimensions from Page 1
                            const firstPage = await pdfDocInstance.getPage(1);
                            const originalViewport = firstPage.getViewport({ scale: 1 });
                            const aspectRatio = originalViewport.width / originalViewport.height;
                            
                            // Calculate responsive dimensions
                            const isMobile = window.innerWidth < 768;
                            
                            // Max height: 80vh desktop, 60vh mobile
                            const maxHeight = isMobile ? window.innerHeight * 0.6 : window.innerHeight * 0.85;
                            // Max width per page: 90vw mobile, 40vw desktop (since it's 2 pages)
                            const maxWidth = isMobile ? window.innerWidth * 0.9 : window.innerWidth * 0.45;
                            
                            // Determine actual dimensions protecting aspect ratio
                            let finalHeight = maxHeight;
                            let finalWidth = finalHeight * aspectRatio;
                            
                            // If width is too big, constrain by width
                            if (finalWidth > maxWidth) {
                                finalWidth = maxWidth;
                                finalHeight = finalWidth / aspectRatio;
                            }

                            this.loadingText = `Rendering ${this.totalPages} Pages...`;

                            // 3. Render Pages
                            const container = document.getElementById('book-container-{{ md5($file) }}');
                            container.innerHTML = ''; // Clear previous

                            for (let pageNum = 1; pageNum <= this.totalPages; pageNum++) {
                                const page = await pdfDocInstance.getPage(pageNum);
                                const viewport = page.getViewport({ scale: finalWidth / page.getViewport({scale:1}).width });

                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = finalHeight; // Force exact height
                                canvas.width = finalWidth;   // Force exact width
                                
                                canvas.className = 'w-full h-full object-contain bg-white'; 
                                
                                await page.render({
                                    canvasContext: context,
                                    viewport: viewport
                                }).promise;
                                
                                const pageWrapper = document.createElement('div');
                                pageWrapper.className = 'page shadow-lg'; 
                                pageWrapper.style.backgroundColor = 'white'; // White paper background
                                pageWrapper.appendChild(canvas);
                                
                                container.appendChild(pageWrapper);
                            }

                            // 4. Initialize PageFlip
                            this.loadingText = 'Starting Animation...';
                            
                            setTimeout(() => {
                                if (bookInstance) bookInstance.destroy();
                                
                                // @ts-ignore
                                bookInstance = new St.PageFlip(container, {
                                    width: finalWidth,
                                    height: finalHeight,
                                    size: 'fixed', // Fixed works better for ensuring exact fit
                                    minWidth: 200,
                                    maxWidth: 1500,
                                    minHeight: 300,
                                    maxHeight: 2000,
                                    maxShadowOpacity: 0.5,
                                    showCover: true,
                                    mobileScrollSupport: false 
                                });

                                bookInstance.loadFromHTML(document.querySelectorAll('#book-container-{{ md5($file) }} > div'));
                                
                                bookInstance.on('flip', (e) => {
                                    this.currentPage = e.data;
                                });

                                this.loading = false;
                            }, 100);

                        } catch (e) {
                            console.error(e);
                            this.error = true;
                            this.errorMessage = e.message;
                            this.loading = false;
                        }
                    },
                    
                    prevPage() {
                        bookInstance?.flipPrev();
                    },
                    
                    nextPage() {
                        bookInstance?.flipNext();
                    }
                };
            });
        });
    </script>
@endonce
