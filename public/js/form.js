

function addCart(item = {}) {
  const template = document.getElementById("produk-template");
  const container = document.getElementById("produk-container");

  const clone = template.content.cloneNode(true);
  container.appendChild(clone);

  const allSelects = container.querySelectorAll(".produk-select");
  const allInputs = container.querySelectorAll('input[name="quantity[]"]');

  const select = allSelects[allSelects.length - 1];
  const input = allInputs[allInputs.length - 1];

  // Pastikan option-nya udah ada, cari di select spesifik
  if (item.item_id) {
    const option = select.querySelector(`option[value="${item.item_id}"]`);
    if (option) {
      option.selected = true;
    } else {
      console.warn(`Option dengan value ${item.item_id} tidak ditemukan`);
    }
  }

  $(select).select2({
    dropdownParent: $("#modal"),
    width: "100%",
  });

  if (item.quantity) {
    input.value = item.quantity;
  }

  container.querySelectorAll(".btn-hapus").forEach((btn) => {
    btn.onclick = function () {
      btn.closest(".produk-item").remove();
    };
  });
}

    document.getElementById("tambah-produk").addEventListener("click", function () {
      addCart();
    });

    const inputDiskon = document.getElementById('diskon');

    inputDiskon.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = formatRupiah(value, 'Rp ');
        } else {
            this.value = '';
        }
    });

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   	 = number_string.split(','),
            sisa     	 = split[0].length % 3,
            rupiah     	 = split[0].substr(0, sisa),
            ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix + rupiah;
    }
