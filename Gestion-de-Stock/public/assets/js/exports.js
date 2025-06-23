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
            
            // Show notification if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Export en cours...',
                    text: 'Préparation de votre fichier d\'export',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
            
            // Reset button state after delay
            setTimeout(function() {
                button.classList.remove('btn-export-loading');
                button.disabled = false;
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Export terminé!',
                        text: 'Votre fichier a été téléchargé avec succès',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            }, 2500);
        });
    });
      // Auto-close dropdown when clicking export items
    document.querySelectorAll('.dropdown-item[href*="export"]').forEach(function(link) {
        link.addEventListener('click', function() {
            // Close the dropdown using Bootstrap 4 method
            const dropdown = this.closest('.dropdown-menu');
            if (dropdown) {
                const toggle = dropdown.previousElementSibling;
                if (toggle && toggle.classList.contains('dropdown-toggle')) {
                    // Use Bootstrap 4 dropdown toggle method
                    $(toggle).dropdown('toggle');
                }
            }
        });
    });
    
    // Add tooltips to export buttons if available
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Handle export error states (if needed)
    window.handleExportError = function(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Erreur d\'export',
                text: message || 'Une erreur est survenue lors de l\'export',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Erreur d\'export: ' + (message || 'Une erreur est survenue'));
        }
    };
});
