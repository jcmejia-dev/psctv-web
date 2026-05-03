window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const header = document.getElementById('mainHeader');
    const texts = document.querySelectorAll('.sidebar-text');
    const title = document.getElementById('sidebarTitle');

    sidebar.classList.toggle('sidebar-expanded');
    sidebar.classList.toggle('sidebar-collapsed');

    content.classList.toggle('content-expanded');
    content.classList.toggle('content-collapsed');

    header.classList.toggle('header-expanded');
    header.classList.toggle('header-collapsed');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('shadow-lg');
        } else {
            header.classList.remove('shadow-lg');
        }
    });

    // Adjust header position when sidebar collapses
    if (sidebar.classList.contains('sidebar-collapsed')) {
        header.classList.remove('left-60');
        header.classList.add('left-20');
    } else {
        header.classList.remove('left-20');
        header.classList.add('left-60');
    }

    texts.forEach(el => {
        el.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'inline';
    });

    if (title) {
        title.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'block';
    }
};

// Modern delete function with enhanced animations and confirmation
window.deleteTechnician = function(id, name) {
    Swal.fire({
        title: `
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 mb-4 relative">
                    <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-75"></div>
                    <div class="relative w-20 h-20 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mt-2">Delete Technician?</h3>
            </div>
        `,
        html: `
            <div class="text-center space-y-3">
                <p class="text-slate-600 leading-relaxed">
                    You're about to delete <strong class="text-red-500">"${name}"</strong>. 
                    This action will permanently remove all associated data and cannot be undone.
                </p>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-amber-800">Warning: This action is permanent</p>
                            <p class="text-xs text-amber-600 mt-1">All service records, user account, and profile data will be deleted.</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: false,
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Yes, Delete Permanently
            </div>
        `,
        cancelButtonText: `
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel
            </div>
        `,
        customClass: {
            popup: 'rounded-2xl shadow-2xl border-0 animate__animated animate__fadeIn animate__faster',
            header: 'p-0 border-0',
            title: 'p-0 m-0',
            htmlContainer: 'mt-2',
            confirmButton: 'px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg',
            cancelButton: 'px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        },
        backdrop: `
            rgba(0, 0, 0, 0.4)
            url("/images/hexagon.svg")
            left top
            no-repeat
        `,
        background: '#ffffff',
        width: '480px',
        padding: '2rem'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show beautiful loading animation
            Swal.fire({
                title: `
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800">Deleting Technician</h3>
                    </div>
                `,
                html: `
                    <p class="text-slate-600 text-center">Please wait while we securely remove "${name}" from the system...</p>
                    <div class="w-full bg-slate-200 rounded-full h-2 mt-4">
                        <div class="bg-blue-500 h-2 rounded-full animate-pulse"></div>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border-0',
                    htmlContainer: 'space-y-3'
                }
            });

            // Send delete request
            fetch(`/admin/technicians/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message with smooth animation
                    Swal.fire({
                        title: `
                            <div class="flex flex-col items-center space-y-3">
                                <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full flex items-center justify-center shadow-lg animate__animated animate__bounceIn">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Successfully Deleted!</h3>
                            </div>
                        `,
                        html: `
                            <div class="text-center space-y-2">
                                <p class="text-slate-600">Technician <strong class="text-emerald-600">"${name}"</strong> has been permanently removed from the system.</p>
                                <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mt-3">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-1a2 2 0 00-2-2H6a2 2 0 00-2 2v1a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Refreshing page...</span>
                                </div>
                            </div>
                        `,
                        icon: false,
                        timer: 2500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border-0 animate__animated animate__fadeInUp',
                            timerProgressBar: 'bg-emerald-500'
                        },
                        willClose: () => {
                            // Add smooth page transition
                            document.body.style.opacity = '0.8';
                            document.body.style.transition = 'opacity 0.3s ease';
                        }
                    });

                    // Reload page after success with smooth transition
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                } else {
                    throw new Error(data.message || 'Failed to delete technician');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    title: `
                        <div class="flex flex-col items-center space-y-3">
                            <div class="w-20 h-20 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg animate__animated animate__shakeX">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Deletion Failed</h3>
                        </div>
                    `,
                    html: `
                        <div class="text-center">
                            <p class="text-slate-600 mb-4">${error.message || 'An unexpected error occurred while deleting the technician.'}</p>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-3">
                                <p class="text-sm text-red-700">Please try again or contact support if the problem persists.</p>
                            </div>
                        </div>
                    `,
                    icon: false,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border-0',
                        confirmButton: 'px-6 py-2.5 rounded-xl font-semibold'
                    }
                });
            });
        }
    });
};

// Real-time filtering for technicians
window.initTechnicianFilters = function() {
    const searchInput = document.querySelector('input[name="search"]');
    const statusSelect = document.querySelector('select[name="status"]');

    if (!searchInput || !statusSelect) return;

    let filterTimeout;

    function applyFilters() {
        const searchValue = searchInput.value;
        const statusValue = statusSelect.value;

        // Build URL with current filters
        const url = new URL(window.location);
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }

        if (statusValue && statusValue !== 'all') {
            url.searchParams.set('status', statusValue);
        } else {
            url.searchParams.delete('status');
        }

        // Reset to page 1 when filtering
        url.searchParams.delete('page');

        // Navigate to filtered results
        window.location.href = url.toString();
    }

    // Debounced search input
    searchInput.addEventListener('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(applyFilters, 500);
    });

    // Immediate status change
    statusSelect.addEventListener('change', applyFilters);
};

// Select all functionality for technicians
window.initSelectAll = function() {
    const selectAllCheckbox = document.getElementById('selectAllTechnicians');
    const individualCheckboxes = document.querySelectorAll('.technician-checkbox');

    if (!selectAllCheckbox || individualCheckboxes.length === 0) return;

    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        individualCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionsVisibility();
    });

    individualCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.technician-checkbox:checked');
            const totalBoxes = document.querySelectorAll('.technician-checkbox');

            selectAllCheckbox.checked = checkedBoxes.length === totalBoxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes.length;

            updateBulkActionsVisibility();
        });
    });

    function updateBulkActionsVisibility() {
        const checkedBoxes = document.querySelectorAll('.technician-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');

        if (bulkActions) {
            if (checkedBoxes.length > 0) {
                bulkActions.classList.remove('hidden');
                bulkActions.classList.add('flex');
            } else {
                bulkActions.classList.add('hidden');
                bulkActions.classList.remove('flex');
            }
        }
    }
};

// Modern bulk delete technicians with enhanced animations
window.bulkDeleteTechnicians = function() {
    const selectedIds = Array.from(document.querySelectorAll('.technician-checkbox:checked')).map(cb => cb.value);

    if (selectedIds.length === 0) {
        Swal.fire({
            title: 'No Selection',
            text: 'Please select at least one technician to delete.',
            icon: 'warning',
            confirmButtonColor: '#f59e0b',
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                confirmButton: 'px-6 py-2.5 rounded-xl font-semibold'
            }
        });
        return;
    }

    const count = selectedIds.length;
    const technicianText = count === 1 ? 'technician' : 'technicians';

    Swal.fire({
        title: `
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 mb-4 relative">
                    <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-75"></div>
                    <div class="relative w-20 h-20 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mt-2">Delete ${count} ${technicianText}?</h3>
            </div>
        `,
        html: `
            <div class="text-center space-y-3">
                <p class="text-slate-600 leading-relaxed">
                    You're about to delete <strong class="text-red-500">${count} ${technicianText}</strong>. 
                    This action will permanently remove all associated data and cannot be undone.
                </p>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-amber-800">Bulk deletion in progress</p>
                            <p class="text-xs text-amber-600 mt-1">This may take a few moments. Please don't close this window.</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: false,
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete All ${count} ${technicianText}
            </div>
        `,
        cancelButtonText: `
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel
            </div>
        `,
        customClass: {
            popup: 'rounded-2xl shadow-2xl border-0 animate__animated animate__fadeIn animate__faster',
            header: 'p-0 border-0',
            title: 'p-0 m-0',
            htmlContainer: 'mt-2',
            confirmButton: 'px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg',
            cancelButton: 'px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show beautiful loading animation for bulk delete
            Swal.fire({
                title: `
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800">Deleting ${count} ${technicianText}</h3>
                    </div>
                `,
                html: `
                    <div class="text-center space-y-3">
                        <p class="text-slate-600">Processing bulk deletion. This may take a moment...</p>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div id="bulkDeleteProgress" class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p id="bulkDeleteStatus" class="text-sm text-slate-500">Starting deletion process...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border-0',
                    htmlContainer: 'space-y-3'
                }
            });

            // Delete technicians one by one with progress tracking
            let completed = 0;
            let errors = 0;
            const progressBar = document.getElementById('bulkDeleteProgress');
            const statusText = document.getElementById('bulkDeleteStatus');

            function updateProgress() {
                const progress = ((completed + errors) / selectedIds.length) * 100;
                if (progressBar) progressBar.style.width = `${progress}%`;
                if (statusText) {
                    statusText.textContent = `Processed ${completed + errors} of ${selectedIds.length} technicians (${completed} deleted, ${errors} failed)`;
                }
            }

            function deleteNext() {
                if (completed + errors >= selectedIds.length) {
                    // All done
                    if (errors === 0) {
                        Swal.fire({
                            title: `
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full flex items-center justify-center shadow-lg animate__animated animate__bounceIn">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">Bulk Delete Complete!</h3>
                                </div>
                            `,
                            html: `
                                <div class="text-center space-y-2">
                                    <p class="text-slate-600">Successfully deleted <strong class="text-emerald-600">${completed} ${technicianText}</strong>.</p>
                                    <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mt-3">
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-1a2 2 0 00-2-2H6a2 2 0 00-2 2v1a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span>Refreshing page...</span>
                                    </div>
                                </div>
                            `,
                            icon: false,
                            timer: 2500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl border-0 animate__animated animate__fadeInUp',
                                timerProgressBar: 'bg-emerald-500'
                            },
                            willClose: () => {
                                document.body.style.opacity = '0.8';
                                document.body.style.transition = 'opacity 0.3s ease';
                            }
                        });
                    } else if (completed > 0) {
                        Swal.fire({
                            title: 'Partially Completed',
                            text: `Deleted ${completed} ${technicianText}, but ${errors} failed.`,
                            icon: 'warning',
                            confirmButtonColor: '#f59e0b',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl',
                                confirmButton: 'px-6 py-2.5 rounded-xl font-semibold'
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed!',
                            text: 'Failed to delete any technicians.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl',
                                confirmButton: 'px-6 py-2.5 rounded-xl font-semibold'
                            }
                        });
                    }

                    // Reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                    return;
                }

                const id = selectedIds[completed + errors];

                fetch(`/admin/technicians/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        completed++;
                    } else {
                        errors++;
                    console.error(`Failed to delete technician ${id}:`, data.message);
                    // You could add specific error handling here
                    // For example, show which technicians failed in the final message
                    if (!window.failedTechnicians) window.failedTechnicians = [];
                    window.failedTechnicians.push({ id, name: 'Technician #' + id, error: data.message });
                    }
                    updateProgress();
                    deleteNext();
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    errors++;
                    if (!window.failedTechnicians) window.failedTechnicians = [];
                    window.failedTechnicians.push({ id, name: 'Technician #' + id, error: error.message });
                    updateProgress();
                    deleteNext();
                });
            }

            deleteNext();
        }
    });
};

// resources/js/admin.js
document.addEventListener('DOMContentLoaded', function () {
  // pick all toggle buttons
  const toggles = document.querySelectorAll('.submenu-toggle');

  toggles.forEach(btn => {
    btn.addEventListener('click', function (e) {
      const targetId = btn.dataset.target;
      if (!targetId) return;
      const menu = document.getElementById(targetId);
      if (!menu) return;

      // ACCORDION: close other menus (optional). Remove this block if you don't want accordion.
      document.querySelectorAll('ul[id$="Menu"]').forEach(ul => {
        if (ul.id !== targetId) {
          ul.classList.add('hidden');
          const c = ul.previousElementSibling?.querySelector('.caret');
          if (c) c.classList.remove('rotate-180');
        }
      });

      // toggle current menu
      menu.classList.toggle('hidden');

      // rotate caret inside the button
      const caret = btn.querySelector('.caret');
      if (caret) caret.classList.toggle('rotate-180');
    });
  });
});