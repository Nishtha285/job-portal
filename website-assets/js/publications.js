/* GENERIC DATA TABLES */

if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
    $('table.display').each(function () {
        const table = $(this);
        if (table.data('server-side') === true) {
            return;
        }

        if (!$.fn.dataTable.isDataTable(this)) {
            table.DataTable({
                responsive: true,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                pageLength: 25,
                language: {
                    search: 'Search:',
                }
            });
            // Initialize bootstrap-select on the length dropdown to ensure the
            // 'Show _MENU_ entries' control is interactive and not clipped.
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