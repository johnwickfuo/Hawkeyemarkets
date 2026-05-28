@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border-color: rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px 12px 0 0;
            padding: 12px !important;
        }

        .ql-container.ql-snow {
            border-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 0 0 12px 12px;
            font-family: inherit;
            font-size: 16px;
            min-height: 400px;
        }

        #email-editor {
            min-height: 400px;
            background: rgba(255, 255, 255, 0.02);
        }

        .ql-editor {
            min-height: 400px;
            color: white;
            padding: 20px !important;
        }

        .ql-editor.ql-blank::before {
            color: rgba(255, 255, 255, 0.2) !important;
            font-style: normal !important;
            left: 20px !important;
        }

        .ql-snow .ql-stroke {
            stroke: #94a3b8 !important;
        }

        .ql-snow .ql-fill {
            fill: #94a3b8 !important;
        }

        .ql-snow .ql-picker {
            color: #94a3b8 !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.users.index') }}"
                        class="p-2 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all group">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h2 class="text-3xl font-black text-white tracking-tight">
                        {{ __('Bulk Email') }}
                    </h2>
                </div>
                <p class="text-text-secondary text-sm">
                    {{ __('Send rich-text communications to your user base.') }}
                </p>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-secondary border border-white/5 rounded-3xl p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-20"></div>

            <form id="bulk-email-form" action="{{ route('admin.users.send-bulk-email') }}" method="POST"
                class="relative z-10 space-y-8">
                @csrf

                {{-- Audience Selection --}}
                <div class="space-y-4">
                    <label
                        class="text-[10px] text-text-secondary uppercase font-black tracking-widest">{{ __('Target Audience') }}</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label
                            class="relative flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02] cursor-pointer hover:border-accent-primary/30 transition-all group">
                            <input type="radio" name="audience" value="all"
                                class="audience-radio w-5 h-5 text-accent-primary bg-white/5 border-white/10 focus:ring-accent-primary ring-offset-secondary">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ __('All Users') }}</h4>
                                <p class="text-text-secondary text-[10px] mt-1">{{ __('Send to every registered user.') }}
                                </p>
                            </div>
                        </label>
                        <label
                            class="relative flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02] cursor-pointer hover:border-accent-primary/30 transition-all group">
                            <input type="radio" name="audience" value="active"
                                class="audience-radio w-5 h-5 text-accent-primary bg-white/5 border-white/10 focus:ring-accent-primary ring-offset-secondary">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ __('All Active Users') }}</h4>
                                <p class="text-text-secondary text-[10px] mt-1">
                                    {{ __('Excludes banned or inactive accounts.') }}</p>
                            </div>
                        </label>
                        <label
                            class="relative flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02] cursor-pointer hover:border-accent-primary/30 transition-all group">
                            <input type="radio" name="audience" value="selected" checked
                                class="audience-radio w-5 h-5 text-accent-primary bg-white/5 border-white/10 focus:ring-accent-primary ring-offset-secondary">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ __('Selected Users') }}</h4>
                                <p class="text-text-secondary text-[10px] mt-1">
                                    {{ __('Handpick specific recipients below.') }}</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Recipient Selection List (Searchable Table) --}}
                <div id="selection-list-container" class="space-y-4 bg-white/5 p-6 rounded-2xl border border-white/5">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                        <h4 class="text-white font-bold text-sm flex items-center gap-2">
                            {{ __('User Selection') }}
                            <span
                                class="px-2 py-0.5 rounded-full bg-accent-primary/20 text-accent-primary text-[10px] font-black"
                                id="selected-count">0</span>
                        </h4>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="user-search" placeholder="{{ __('Search name, email, status...') }}"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-10 py-2.5 text-xs text-white placeholder-text-secondary focus:ring-1 focus:ring-accent-primary outline-none transition-all">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="overflow-hidden border border-white/5 rounded-xl">
                        <div
                            class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-white/[0.02] sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 border-b border-white/5 w-10">
                                            <input type="checkbox" id="select-all-filtered"
                                                class="w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary cursor-pointer">
                                        </th>
                                        <th
                                            class="px-4 py-3 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                            {{ __('User') }}</th>
                                        <th
                                            class="px-4 py-3 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                            {{ __('Email') }}</th>
                                        <th
                                            class="px-4 py-3 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                            {{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="user-list-body">
                                    @forelse($users as $user)
                                        <tr class="user-row hover:bg-white/[0.02] transition-colors border-b border-white/5 last:border-0"
                                            data-search="{{ strtolower($user->username . ' ' . $user->fullname . ' ' . $user->email . ' ' . $user->status) }}">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="ids[]" value="{{ $user->id }}"
                                                    class="recipient-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary cursor-pointer"
                                                    {{ in_array($user->id, $selected_ids) ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-white font-bold">{{ $user->username }}</span>
                                                    <span
                                                        class="text-[10px] text-text-secondary">{{ $user->fullname }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-text-secondary">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if ($user->status == 'active')
                                                    <span
                                                        class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-bold">{{ __('Active') }}</span>
                                                @elseif($user->status == 'banned')
                                                    <span
                                                        class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-bold">{{ __('Banned') }}</span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-bold">{{ ucfirst($user->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-4 py-8 text-center text-text-secondary text-sm italic">
                                                {{ __('No users found.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Subject --}}
                <div class="space-y-2">
                    <label
                        class="text-[10px] text-text-secondary uppercase font-black tracking-widest">{{ __('Email Subject') }}</label>
                    <input type="text" name="subject" placeholder="{{ __('Enter subject...') }}" required
                        class="w-full bg-white/[0.02] border border-white/10 rounded-2xl px-6 py-4 text-white text-lg placeholder-text-secondary/50 focus:ring-2 focus:ring-accent-primary focus:border-transparent outline-none transition-all">
                </div>

                {{-- Message Editor --}}
                <div class="space-y-4">
                    <label
                        class="text-[10px] text-text-secondary uppercase font-black tracking-widest">{{ __('Email Body') }}</label>
                    <div id="email-editor" class="rounded-2xl overflow-hidden"></div>
                    <input type="hidden" name="message" id="message-input">
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-white/5 flex items-center justify-between gap-4">
                    <div class="text-text-secondary text-xs italic">
                        {{ __('Tip: Use rich formatting to engage your users.') }}
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-8 py-4 rounded-2xl border border-white/10 text-white font-bold hover:bg-white/5 transition-all">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-10 py-4 rounded-2xl bg-accent-primary text-white font-black shadow-2xl shadow-accent-primary/40 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                            {{ __('Dispatch Emails') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="sending-overlay"
        class="fixed inset-0 bg-primary-dark/90 backdrop-blur-md z-[200] flex flex-col items-center justify-center hidden opacity-0 transition-opacity duration-500">
        <div class="w-20 h-20 border-4 border-accent-primary border-t-transparent rounded-full animate-spin mb-8"></div>
        <h3 class="text-2xl font-black text-white tracking-widest uppercase mb-2">{{ __('Dispatching In Progress') }}</h3>
        <p class="text-text-secondary font-medium tracking-wide">{{ __('Please do not close this window.') }}</p>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill
            var quill = new Quill('#email-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        ['link', 'image', 'clean']
                    ]
                },
                placeholder: '{{ __('Write your email message here...') }}'
            });

            // Update count on load
            updateSelectedCount();

            // Audience Toggle
            $('.audience-radio').change(function() {
                const val = $(this).val();
                if (val === 'selected') {
                    $('#selection-list-container').fadeIn().removeClass('hidden');
                } else {
                    $('#selection-list-container').fadeOut();
                }
                updateSelectedCount();
            });

            // Search Functionality
            $('#user-search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#user-list-body tr').each(function() {
                    $(this).toggle($(this).data('search').indexOf(value) > -1);
                });

                // Reset select all checkbox on search
                $('#select-all-filtered').prop('checked', false);
            });

            // Select All Filtered Users
            $('#select-all-filtered').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('#user-list-body tr:visible .recipient-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Update Count on Individual Checkbox Change
            $(document).on('change', '.recipient-checkbox', function() {
                updateSelectedCount();

                // Update select all state
                const visibleCount = $('#user-list-body tr:visible').length;
                const checkedVisibleCount = $('#user-list-body tr:visible .recipient-checkbox:checked')
                    .length;
                $('#select-all-filtered').prop('checked', visibleCount > 0 && visibleCount ===
                    checkedVisibleCount);
            });

            function updateSelectedCount() {
                const audience = $('.audience-radio:checked').val();
                let count = 0;

                if (audience === 'all') {
                    count = {{ $users->count() }};
                } else if (audience === 'active') {
                    count = {{ $users->where('status', 'active')->count() }};
                } else {
                    count = $('.recipient-checkbox:checked').length;
                }

                $('#selected-count').text(count);
            }

            // Form Submission
            $('#bulk-email-form').on('submit', function(e) {
                e.preventDefault();

                const message = quill.root.innerHTML;
                if (quill.getText().trim() === '') {
                    toastNotification('{{ __('Please enter a message.') }}', 'error');
                    return;
                }

                const audience = $('input[name="audience"]:checked').val();
                if (audience === 'selected' && $('.recipient-checkbox:checked').length === 0) {
                    toastNotification('{{ __('Please select at least one recipient.') }}', 'error');
                    return;
                }

                $('#message-input').val(message);
                const formData = $(this).serialize();
                const $btn = $(this).find('button[type="submit"]');
                const $overlay = $('#sending-overlay');

                $overlay.removeClass('hidden').addClass('flex').animate({
                    opacity: 1
                }, 500);
                $btn.prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('Success!') }}',
                                text: response.message,
                                background: '#1e293b',
                                color: '#fff',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.href =
                                    "{{ route('admin.users.index') }}";
                            });
                        } else {
                            toastNotification(response.message ||
                                '{{ __('An error occurred.') }}', 'error');
                            $overlay.animate({
                                opacity: 0
                            }, 300, function() {
                                $(this).addClass('hidden').removeClass('flex');
                            });
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON ? xhr.responseJSON.message :
                            "{{ __('An error occurred while sending emails.') }}";
                        toastNotification(error, 'error');
                        $overlay.animate({
                            opacity: 0
                        }, 300, function() {
                            $(this).addClass('hidden').removeClass('flex');
                        });
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
