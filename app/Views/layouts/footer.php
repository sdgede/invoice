<script src="<?= base_url('/js/bootstrap.min.js') ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script> -->


<script>
    $('#productModal').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#productModal'),
            placeholder: "Pilih produk",
            allowClear: true
        });
    });

    $('.editProductModal').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('.editProductModal'),
            placeholder: "Pilih produk",
            allowClear: true
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<script>
    setTimeout(function () {
        var alertSuccess = document.getElementById('alert-success');
        if (alertSuccess) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alertSuccess);
            bsAlert.close();
        }
        var alertError = document.getElementById('alert-error');
        if (alertError) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alertError);
            bsAlert.close();
        }
    }, 3000);
</script>

<script>
    $('#exampleModal').on('shown.bs.modal', function () {
        $('#produk').select2({
            dropdownParent: $('#exampleModal'),
            placeholder: "Pilih satu atau lebih produk...",
            allowClear: true
        });
    });
</script>

<!-- alert -->
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000",
        "showDuration": "300",
        "hideDuration": "1000",
        "extendedTimeOut": "1000"
    }
</script>

<script>
    function handlePrintAndDelete(code) {
        if (!confirm('Yakin ingin menghapus invoice ini?')) return;

        // Submit POST ke print dulu (PDF di-download)
        document.getElementById('printForm' + code).submit();

        // Setelah 2 detik, submit form delete (POST juga)
        setTimeout(() => {
            document.getElementById('deleteForm' + code).submit();
        }, 3000);
    }

    function formatedDate(tanggal) {
        let date = new Date(tanggal);
        return formattedDate = date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    document.querySelectorAll('.dropdown-toggles').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const parent = this.closest('.dropdown2');
            document.querySelectorAll('.dropdown2.open').forEach(d => {
                if (d !== parent) d.classList.remove('open');
            });
            parent.classList.toggle('open');
        });
    });

    window.addEventListener("click", function () {
        document.querySelectorAll(".dropdown2.open").forEach(drop => {
            drop.classList.remove("open");
        });
    });


    
</script>


</body>

</html>
<!DOCTYPE html>