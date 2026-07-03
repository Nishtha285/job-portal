function initClientSideDataTables() {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('table.display').each(function () {
            const table = $(this);

            // If it happens to have server-side="true", skip it!
            if (table.data('server-side') === true) {
                return;
            }

            // Otherwise, initialize it normally
            if (!$.fn.dataTable.isDataTable(this)) {
                table.DataTable({
                    responsive: true,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    pageLength: 25,
                    language: {
                        search: 'Search:',
                    }
                });

                // Initialize bootstrap-select wrapper fix
                try {
                    const wrapper = table.closest('.dataTables_wrapper');
                    const lengthSelect = wrapper.find('select');
                    if (typeof $.fn.selectpicker !== 'undefined' && lengthSelect.length) {
                        lengthSelect.addClass('selectpicker').selectpicker({ container: 'body' });
                    }
                } catch (e) {
                    // ignore any selectpicker errors
                }
            }
        });
    }
}

// Fire it up when the DOM is ready!
$(document).ready(function() {
    initClientSideDataTables();
});