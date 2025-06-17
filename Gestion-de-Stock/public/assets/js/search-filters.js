// Search & Filter functionality for Gestion-de-Stock
document.addEventListener('DOMContentLoaded', function() {    // Auto-expand filter section if there are active filters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('category') || urlParams.has('type') || 
        urlParams.has('role') || (urlParams.has('sort') && urlParams.get('sort') !== 'date_desc')) {
        const filterCollapse = document.getElementById('searchCollapse');
        if (filterCollapse) {
            const bsCollapse = new bootstrap.Collapse(filterCollapse, {
                toggle: true
            });
        }
    }

    // Handle reset buttons
    const resetButtons = document.querySelectorAll('.btn-reset-filters');
    resetButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.getAttribute('href');
        });
    });

    // Enable datepicker for date fields if any
    if (typeof $.fn.datepicker !== 'undefined') {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    // Dynamic filter form submission - submit when select fields change
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
