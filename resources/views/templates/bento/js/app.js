import './bootstrap';

// Global Toast Notification using SweetAlert2 (CDN)
window.toastNotification = function (text, type = 'success') {
    let iconColor;
    let progressBarClass = 'bg-accent-orange'; // Default backup

    switch (type) {
        case 'success':
            iconColor = '#10b981'; // Emerald-500
            progressBarClass = 'bg-emerald-500';
            break;
        case 'error':
            iconColor = '#ef4444'; // Red-500
            progressBarClass = 'bg-red-500';
            break;
        case 'warning':
            iconColor = '#f59e0b'; // Amber-500
            progressBarClass = 'bg-amber-500';
            break;
        case 'info':
            iconColor = '#3b82f6'; // Blue-500
            progressBarClass = 'bg-blue-500';
            break;
        case 'question':
            iconColor = '#8b5cf6'; // Violet-500
            progressBarClass = 'bg-violet-500';
            break;
        default:
            iconColor = '#ff5500'; // Theme Accent
            progressBarClass = 'bg-accent-orange';
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: 'rgba(31, 31, 34, 0.9)', // secondary-dark with blur
        color: '#ffffff',
        iconColor: iconColor,
        customClass: {
            popup: 'backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-4',
            timerProgressBar: progressBarClass,
            title: 'text-sm font-semibold'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInRight animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutRight animate__faster'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: text
    });
}

// Global Styled Confirmation helper
window.confirmAction = function (title = 'Are you sure?', text = '', icon = 'warning', confirmText = 'Yes, proceed', isDanger = false, cancelText = 'Cancel') {
    if (typeof title === 'object' && title !== null) {
        const config = title;
        title = config.title || 'Are you sure?';
        text = config.text || '';
        icon = config.icon || 'warning';
        confirmText = config.confirmButtonText || config.confirmText || 'Yes, proceed';
        cancelText = config.cancelButtonText || config.cancelText || 'Cancel';
        isDanger = config.isDanger || config.danger || config.mode === 'danger' || false;
    }

    const confirmColor = isDanger ? '#ef4444' : '#8b5cf6'; // Red-500 or accent-primary
    const confirmBtnClass = isDanger
        ? 'px-6 py-2.5 rounded-xl font-semibold bg-red-500 text-white shadow-lg shadow-red-500/20 transition-transform hover:scale-105 active:scale-95'
        : 'px-6 py-2.5 rounded-xl font-semibold bg-accent-primary text-white shadow-lg shadow-accent-primary/20 transition-transform hover:scale-105 active:scale-95';

    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: 'rgba(255,255,255,0.05)',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        background: '#131722', // primary-dark
        color: '#ffffff',
        backdrop: `rgba(0,0,0,0.6) backdrop-blur-sm`,
        customClass: {
            popup: 'border border-white/10 rounded-3xl shadow-2xl p-6 glow-border',
            title: 'text-xl font-heading font-bold mb-2',
            htmlContainer: 'text-sm text-text-secondary mb-6',
            confirmButton: confirmBtnClass,
            cancelButton: 'px-6 py-2.5 rounded-xl font-semibold border border-white/10 hover:bg-white/5 transition-colors text-white/70'
        },
        buttonsStyling: false
    }).then((result) => {
        return result.isConfirmed;
    });
}



// Global form submission handler
$(document).on('submit', '.ajax-form', function (e) {
    e.preventDefault();

    let form = $(this);
    let submitBtn = form.find('button[type="submit"]');
    let originalBtnText = submitBtn.html();

    // Disable button and show spinner
    let loadingText = submitBtn.data('loading-text') || 'Loading...';
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingText);

    $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: new FormData(this),
        processData: false,
        contentType: false,
        complete: function () {
            // Check if form has captcha and reset it
            if (window.grecaptcha && form.find('.g-recaptcha').length > 0) {
                grecaptcha.reset();
            }
        },
        success: function (response) {
            if (response.status === 'success') {
                if (typeof toastNotification === 'function') {
                    toastNotification(response.message, 'success');
                }

                let action = form.data('action');

                if (action === 'redirect') {
                    // Check if form has data-redirect, otherwise use server response
                    let redirectUrl = form.data('redirect') || response.redirect;
                    if (redirectUrl) {
                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                    // Do NOT re-enable button
                } else if (action === 'reload') {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    // Do NOT re-enable button
                } else {
                    // unexpected success action or reset
                    if (action === 'reset') {
                        form.trigger('reset');
                    }
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            } else {
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (typeof toastNotification === 'function') {
                    toastNotification(response.message, 'error');
                }
            }
        },
        error: function (xhr) {
            submitBtn.prop('disabled', false).html(originalBtnText);
            let message = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }

            if (typeof toastNotification === 'function') {
                toastNotification(message, 'error');
            }
        }
    });
});


// Mobile Sidebar Toggle - logic moved to blade template for granular control
