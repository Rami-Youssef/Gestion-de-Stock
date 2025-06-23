/**
 * Export functionality JavaScript
 * Handles loading states and user feedback for export operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle export button clicks
    document.querySelectorAll('.dropdown-item[href*="export"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const button = this.closest('.btn-group').querySelector('.dropdown-toggle');
            const originalText = button.innerHTML;
            
            // Add loading state
            button.classList.add('btn-export-loading');
            button.disabled = true;
            
            // Show toast notification
            if (typeof toastr !== 'undefined') {
                toastr.info('Préparation de l\'export en cours...', 'Export', {
                    timeOut: 3000,
                    progressBar: true
                });
            }
            
            // Reset button state after delay
            setTimeout(function() {
                button.classList.remove('btn-export-loading');
                button.disabled = false;
                
                if (typeof toastr !== 'undefined') {
                    toastr.success('Export terminé avec succès!', 'Export', {
                        timeOut: 2000
                    });
                }
            }, 2000);
        });
    });
    
    // Auto-close dropdown when clicking export items
    document.querySelectorAll('.dropdown-item[href*="export"]').forEach(function(link) {
        link.addEventListener('click', function() {
            // Close the dropdown
            const dropdown = this.closest('.dropdown-menu');
            if (dropdown) {
                const toggle = dropdown.previousElementSibling;
                if (toggle && toggle.classList.contains('dropdown-toggle')) {
                    // Simulate bootstrap dropdown close
                    dropdown.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
    
    // Add tooltips to export buttons if available
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
});
