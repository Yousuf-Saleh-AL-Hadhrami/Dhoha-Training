<script src='<?= BASE_URL ?>/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js'></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.querySelector("select[name='device_type_id']").addEventListener("change", function () {
    let typeID = this.value;

    fetch("get_companies.php?type_id=" + typeID)
        .then(res => res.json())
        .then(data => {
            let companySelect = document.querySelector("select[name='device_company_id']");
            companySelect.innerHTML = '<option value="">-- اختر اسم الطراز --</option>';

            data.forEach(item => {
                companySelect.innerHTML += `<option value="${item.id}">${item.device_company_name}</option>`;
            });

            document.querySelector("select[name='device_model_id']").innerHTML = '<option value="">-- اختر اسم الموديل --</option>';
        });
});

document.querySelector("select[name='device_company_id']").addEventListener("change", function () {
    let companyID = this.value;

    fetch("get_models.php?company_id=" + companyID)
        .then(res => res.json())
        .then(data => {
            let modelSelect = document.querySelector("select[name='device_model_id']");
            modelSelect.innerHTML = '<option value="">-- اختر اسم الموديل --</option>';

            data.forEach(item => {
                modelSelect.innerHTML += `<option value="${item.id}">${item.device_model}</option>`;
            });
        });




    /********** Other AJAX Functionalities **********/

    // Search stores dynamically
    $('#search').on('keyup', function() {
        var query = $(this).val();
        $.ajax({
            url: 'ajax/stores_search.php',  
            type: 'GET',
            data: { search: query },
            success: function(data) {
                $('#table-body').html(data);
            }
        });
    });

    // Filter by store select
    $('#store-search').on('change', function() {
        var storeId = $(this).val();
        if (storeId !== '') {
            $.ajax({
                url: 'ajax/store.php',
                type: 'GET',
                data: { store_id: storeId },
                success: function(data) {
                    $('#search-results').html(data);
                    $('.table-responsive').hide(); 
                }
            });
        } else {
            $('#search-results').html('');
            $('.table-responsive').show(); 
        }
    });

    // Search by serial number dynamically
    $('#search_serial').on('keyup', function() {
        let serial = $(this).val();
        $.ajax({
            url: 'ajax/expense_search.php',
            type: 'GET',
            data: { serial_number: serial },
            success: function(data) {
                $('#table_container').html(data);
            }
        });
    });

});
</script>
</body>
</html>
