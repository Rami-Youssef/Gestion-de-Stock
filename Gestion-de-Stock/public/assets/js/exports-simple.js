/**
 * Export functionality JavaScript
 * Simplified version to avoid dropdown conflicts
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle export button clicks with simple feedback
    document.querySelectorAll('.dropdown-item[href*="export"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const button = this.closest('.btn-group').querySelector('.dropdown-toggle');
            
            // Add loading state
            button.classList.add('btn-export-loading');
            button.disabled = true;
            
            // Simple console feedback for debugging
            console.log('Export started for:', this.href);
            
            // Reset button state after delay
            setTimeout(function() {
                button.classList.remove('btn-export-loading');
                button.disabled = false;
                console.log('Export button reset');
            }, 3000);
        });
    });
    
    // Add tooltips to export buttons if available
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    console.log('Export functionality loaded');
});
