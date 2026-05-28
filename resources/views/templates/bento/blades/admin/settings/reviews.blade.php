@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        .settings-section {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .review-row {
            padding: 1.25rem 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .review-row:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .rating-star {
            color: #f59e0b;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background-color: #0f172a;
            margin: 5% auto;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 90%;
            max-width: 600px;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-4xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                            {{ $page_title }}
                        </h2>
                        <p class="text-slate-500 text-sm font-medium tracking-wide">
                            {{ __('Manage your platform\'s client testimonials and reviews.') }}
                        </p>
                    </div>
                    <button onclick="openCreateModal()"
                        class="px-6 py-3 bg-accent-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-accent-secondary transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Add Review') }}
                    </button>
                </div>

                <div class="settings-section">
                    <div class="flex flex-col gap-4">
                        @forelse ($reviews as $review)
                            <div class="review-row">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden border border-white/5 flex-shrink-0 bg-white/5">
                                        @if($review->image)
                                            <img src="{{ asset('assets/images/team/' . $review->image) }}"
                                                alt="{{ $review->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-600">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="text-base font-bold text-white">{{ $review->name }}</span>
                                            <span class="text-xs text-accent-primary font-bold uppercase tracking-widest">{{ $review->role }}</span>
                                            <div class="flex items-center gap-1 ml-2">
                                                @for($i = 0; $i < $review->rating; $i++)
                                                    <svg class="w-3 h-3 rating-star" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium leading-relaxed line-clamp-1 max-w-md">
                                            {{ $review->review }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        onclick="openEditModal({{ $review->id }}, '{{ addslashes($review->name) }}', '{{ addslashes($review->role) }}', '{{ addslashes($review->review) }}', {{ $review->rating }}, '{{ route('admin.settings.reviews.update', $review->id) }}', '{{ $review->image ? asset('assets/images/team/' . $review->image) : '' }}')"
                                        class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal({{ $review->id }}, '{{ route('admin.settings.reviews.delete', $review->id) }}')"
                                        class="p-2 text-red-400/60 hover:text-red-400 hover:bg-red-400/5 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center">
                                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-white font-bold mb-1">{{ __('No reviews yet') }}</h3>
                                <p class="text-slate-500 text-sm">{{ __('Start by adding your first client review.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-white">{{ __('Add Review') }}</h3>
                <button onclick="closeModal('createModal')" class="text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="createForm" action="{{ route('admin.settings.reviews.create') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Full Name') }}</label>
                        <input type="text" name="name" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Role/Title') }}</label>
                        <input type="text" name="role" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Rating') }}</label>
                        <select name="rating" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                            <option value="5" class="bg-[#0f172a]">5 Stars</option>
                            <option value="4" class="bg-[#0f172a]">4 Stars</option>
                            <option value="3" class="bg-[#0f172a]">3 Stars</option>
                            <option value="2" class="bg-[#0f172a]">2 Stars</option>
                            <option value="1" class="bg-[#0f172a]">1 Star</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Review Message') }}</label>
                        <textarea name="review" rows="4" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all resize-none"></textarea>
                    </div>
                    <div class="flex flex-col gap-4 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 bg-white/5">
                                <img id="create-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <div id="create-placeholder" class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="file" name="image" accept="image/*"
                                onchange="previewImage(this, 'create-preview', 'create-placeholder')"
                                class="text-slate-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-white/5 gap-3">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="submit"
                        class="px-8 py-3 bg-accent-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-accent-secondary transition-all flex items-center gap-2">
                        <span class="btn-text">{{ __('Create Review') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-white">{{ __('Edit Review') }}</h3>
                <button onclick="closeModal('editModal')" class="text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Full Name') }}</label>
                        <input type="text" name="name" id="edit-name" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Role/Title') }}</label>
                        <input type="text" name="role" id="edit-role" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Rating') }}</label>
                        <select name="rating" id="edit-rating" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                            <option value="5" class="bg-[#0f172a]">5 Stars</option>
                            <option value="4" class="bg-[#0f172a]">4 Stars</option>
                            <option value="3" class="bg-[#0f172a]">3 Stars</option>
                            <option value="2" class="bg-[#0f172a]">2 Stars</option>
                            <option value="1" class="bg-[#0f172a]">1 Star</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Review Message') }}</label>
                        <textarea name="review" id="edit-review" rows="4" required
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all resize-none"></textarea>
                    </div>
                    <div class="flex flex-col gap-4 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Profile Image (Optional)') }}</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 bg-white/5">
                                <img id="edit-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <div id="edit-placeholder" class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="file" name="image" accept="image/*"
                                onchange="previewImage(this, 'edit-preview', 'edit-placeholder')"
                                class="text-slate-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-white/5 gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="submit"
                        class="px-8 py-3 bg-accent-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-accent-secondary transition-all flex items-center gap-2">
                        <span class="btn-text">{{ __('Update Review') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="modal">
        <div class="modal-content text-center max-w-sm">
            <div class="w-20 h-20 bg-red-400/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">{{ __('Delete Review?') }}</h3>
            <p class="text-slate-500 text-sm mb-8">{{ __('This action is permanent and will remove the review from the database.') }}</p>

            <form id="deleteForm" method="POST">
                @csrf
                <div class="flex flex-col gap-3">
                    <button type="submit"
                        class="w-full py-4 bg-red-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all">{{ __('Yes, Delete Review') }}</button>
                    <button type="button" onclick="closeModal('deleteModal')"
                        class="w-full py-4 bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-widest rounded-xl hover:text-white transition-all">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openCreateModal() {
            $('#createForm')[0].reset();
            $('#create-preview').addClass('hidden');
            $('#create-placeholder').removeClass('hidden');
            $('#createModal').fadeIn(300);
        }

        function openEditModal(id, name, role, review, rating, url, image) {
            $('#edit-name').val(name);
            $('#edit-role').val(role);
            $('#edit-review').val(review);
            $('#edit-rating').val(rating);
            $('#editForm').attr('action', url);

            if (image) {
                $('#edit-preview').attr('src', image).removeClass('hidden');
                $('#edit-placeholder').addClass('hidden');
            } else {
                $('#edit-preview').addClass('hidden');
                $('#edit-placeholder').removeClass('hidden');
            }

            $('#editModal').fadeIn(300);
        }

        function previewImage(input, previewId, placeholderId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).removeClass('hidden');
                    $('#' + placeholderId).addClass('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openDeleteModal(id, url) {
            $('#deleteForm').attr('action', url);
            $('#deleteModal').fadeIn(300);
        }

        function closeModal(id) {
            $('#' + id).fadeOut(200);
        }

        // Close on outside click
        $(window).on('click', function(e) {
            if ($(e.target).hasClass('modal')) {
                $('.modal').fadeOut(200);
            }
        });

        $(document).ready(function() {
            $('#createForm, #editForm, #deleteForm').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                const $btnText = $btn.find('.btn-text');
                const originalText = $btnText.text() || $btn.text();

                const formData = new FormData(this);

                $btn.prop('disabled', true).addClass('opacity-70');
                if ($btnText.length) $btnText.text('{{ __('Processing...') }}');
                else $btn.text('{{ __('Processing...') }}');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastNotification(response.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || '{{ __('Something went wrong.') }}';
                        toastNotification(errorMessage, 'error');
                        $btn.prop('disabled', false).removeClass('opacity-70');
                        if ($btnText.length) $btnText.text(originalText);
                        else $btn.text(originalText);
                    }
                });
            });
        });
    </script>
@endsection
