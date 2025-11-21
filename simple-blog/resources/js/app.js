import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

import Swal from 'sweetalert2';

window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;

Alpine.start();

// Global Delete Confirmation
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('submit', function (e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827', // gray-900
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
    // Generic Confirmation Form
    document.body.addEventListener('submit', function (e) {
        if (e.target.classList.contains('confirm-form')) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: form.dataset.title || 'Are you sure?',
                text: form.dataset.text || "You won't be able to revert this!",
                icon: form.dataset.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: form.dataset.confirmColor || '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: form.dataset.confirmText || 'Yes, do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
