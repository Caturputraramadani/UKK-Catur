<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/iconify-icon/dist/iconify-icon.min.js') }}"></script>
<script src="{{ asset('assets/libs/@preline/dropdown/index.js') }}"></script>
<script src="{{ asset('assets/libs/@preline/overlay/index.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>


{{-- Modal User --}}
 <script type="text/javascript">

            function openUserModal(user = null) {
                const modal = document.getElementById('dataModal');
                const modalTitle = document.getElementById('modalTitle');
                const form = document.getElementById('userForm');


                if (user) {
                    modalTitle.innerText = 'Edit User';
                    form.action = '{{ url('user') }}/' + user.id;
                    document.getElementById('email').value = user.email;
                    document.getElementById('password').value = user.password;
                    document.getElementById('name').value = user.name;
                    document.getElementById('role').value = user.role;
                } else {
                    modalTitle.innerText = 'Add User';
                    form.action = '{{ route('users.save') }}';
                }

                modal.classList.remove('hidden');
            }


            function closeUserModal() {
                const modal = document.getElementById('dataModal');
                modal.classList.add('hidden');
            }


            function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg mx-1',
                    cancelButton: 'bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg mx-1'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.error || 'Failed to delete user');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.success,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg'
                            }
                        });
                        setTimeout(() => window.location.reload(), 1000);
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg'
                            }
                        });
                    });
                }
            });
            }
</script>

{{-- Modal Product --}}
<script type="text/javascript">

    function openProductModal(product = null) {
        const modal = document.getElementById('dataModal');
        const modalTitle = document.getElementById('modalTitle');
        const form = document.getElementById('productForm');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const stockInput = document.getElementById('stock');
        const priceInput = document.getElementById('price');


        form.reset();
        imagePreviewContainer.classList.add('hidden');
        stockInput.removeAttribute('readonly');

        if (product) {
            modalTitle.innerText = 'Edit Product';
            form.action = '{{ url('product') }}/' + product.id;
            document.getElementById('name').value = product.name;


            priceInput.value = formatRupiahValue(product.price);

            stockInput.value = product.stock;
            stockInput.setAttribute('readonly', true);

            if (product.images) {
                imagePreview.src = '{{ asset('storage/') }}' + '/' + product.images;
                imagePreviewContainer.classList.remove('hidden');
            }
        } else {
            modalTitle.innerText = 'Add Product';
            form.action = '{{ route('products.save') }}';
        }

        modal.classList.remove('hidden');
    }



    function closeProductModal() {
        const modal = document.getElementById('dataModal');
        modal.classList.add('hidden');
    }


    function previewImage(event) {
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }



    function deleteProduct(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg mx-1',
                cancelButton: 'bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg mx-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || 'Failed to delete product');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.success,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        customClass: {
                            confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg'
                        }
                    });
                    setTimeout(() => window.location.reload(), 1000);
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        customClass: {
                            confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg'
                        }
                    });
                });
            }
        });
    }




    function formatRupiah(input) {
    let value = input.value.replace(/[^0-9]/g, "");
    if (!value) value = "0";
    input.value = formatRupiahValue(value);
    }

    function formatRupiahValue(value) {
        return "Rp " + parseInt(value, 10).toLocaleString("id-ID");
    }

    document.addEventListener("DOMContentLoaded", function () {
    const productForm = document.getElementById("productForm");
    if (productForm) {
        productForm.addEventListener("submit", function(event) {
            let priceInput = document.getElementById("price");
            priceInput.value = priceInput.value.replace(/\D/g, "");
        });
    }
    });




</script>

{{-- Modal Update Stock Product --}}
<script>
    function openStockModal(product) {
        const modal = document.getElementById('stockModal');
        const form = document.getElementById('stockForm');
        const stockInput = document.getElementById('updateStock');
        const productNameInput = document.getElementById('productName');

        form.action = '{{ url('product') }}/' + product.id;
        stockInput.value = product.stock;
        productNameInput.value = product.name;

        modal.classList.remove('hidden');
    }

    function closeStockModal() {
        const modal = document.getElementById('stockModal');
        modal.classList.add('hidden');
    }
</script>


 {{-- Script Create Sales --}}
 <script>
    document.addEventListener('DOMContentLoaded', function () {
        const productCards = document.querySelectorAll('.quantity');
        const nextBtn = document.getElementById('nextBtn');
        const productSelectionForm = document.getElementById('productSelectionForm');
        const selectedProductsInput = document.getElementById('selectedProductsInput');

        let selectedProducts = [];

        productCards.forEach(input => {
            const productId = input.dataset.id;
            const price = parseFloat(input.dataset.price);
            const maxStock = parseInt(input.getAttribute('max'));

            input.addEventListener('input', function () {
                updateQuantity(productId, this.value, price, maxStock);
            });

            document.querySelector(`.increment[data-id="${productId}"]`).addEventListener('click', function () {
                let value = parseInt(input.value) + 1;
                if (value <= maxStock) {
                    input.value = value;
                    updateQuantity(productId, value, price, maxStock);
                }
            });

            document.querySelector(`.decrement[data-id="${productId}"]`).addEventListener('click', function () {
                let value = parseInt(input.value) - 1;
                if (value >= 0) {
                    input.value = value;
                    updateQuantity(productId, value, price, maxStock);
                }
            });
        });

        function updateQuantity(id, quantity, price, maxStock) {
            quantity = Math.max(0, Math.min(quantity, maxStock));


            const subtotalElement = document.querySelector(`.subtotal[data-id="${id}"]`);
            subtotalElement.textContent = `Rp. ${new Intl.NumberFormat('id-ID').format(quantity * price)}`;


            const existingIndex = selectedProducts.findIndex(p => p.id === id);
            if (quantity > 0) {
                if (existingIndex >= 0) {
                    selectedProducts[existingIndex].quantity = quantity;
                    selectedProducts[existingIndex].subtotal = quantity * price;
                } else {
                    selectedProducts.push({ id, quantity, price, subtotal: quantity * price });
                }
            } else {
                selectedProducts = selectedProducts.filter(p => p.id !== id);
            }

            nextBtn.disabled = selectedProducts.length === 0;
            selectedProductsInput.value = JSON.stringify(selectedProducts);
        }


        productSelectionForm.addEventListener('submit', function(e) {
            if (selectedProducts.length === 0) {
                e.preventDefault();
                alert('Pilih setidaknya satu produk untuk melanjutkan');
            }
        });
    });
</script>


{{-- Script Post Create --}}
<script>

    function formatAndCheckPayment() {
        let input = document.getElementById('amount_paid');
        let value = input.value.replace(/\D/g, "");
        let formattedValue = new Intl.NumberFormat('id-ID').format(value);
        input.value = formattedValue;

        checkPayment(value);
    }

    function checkPayment(amountPaid) {
        let totalAmount = {{ $total ?? 0 }};
        let warningText = document.getElementById('paymentWarning');

        if (parseInt(amountPaid.replace(/\./g, "")) < totalAmount) {
            warningText.classList.remove('hidden');
        } else {
            warningText.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const memberSelect = document.getElementById('memberSelect');
        const memberFields = document.getElementById('memberFields');
        const paymentForm = document.getElementById('paymentForm');


        memberSelect.addEventListener('change', function () {
            memberFields.classList.toggle('hidden', this.value !== "1");
        });

        
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Convert formatted amount back to raw number
            const amountInput = document.getElementById('amount_paid');
            amountInput.value = amountInput.value.replace(/\./g, '');

            // Submit the form
            this.submit();
        });
    });
</script>


{{-- Script Member Payment --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('usePoints');
    const input = document.getElementById('availablePoint');

    if (checkbox && input) {
        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                input.classList.add('text-green-600', 'font-semibold');
            } else {
                input.classList.remove('text-green-600', 'font-semibold');
            }
        });
    }
    });

</script>


{{-- Script Modal Sales --}}
<script>
    function showSalesDetail(saleId) {
      fetch(`/sales/${saleId}/detail`)
          .then(response => response.json())
          .then(data => {
              // Populate modal content here
              document.getElementById('salesDetailModalLabel').textContent = 'Detail Penjualan';
              document.getElementById('salesDetailModal').classList.remove('hidden');

              // Member details
              document.getElementById('memberStatus').textContent = data.member ? 'Member' : 'Non-Member';
              document.getElementById('memberPhone').textContent = data.member ? data.member.no_telephone : '-';
              document.getElementById('memberPoints').textContent = data.member ? data.member.point : 0;
              document.getElementById('memberSince').textContent = data.member ? data.member.date : '-';

              // Sale details table
              let saleDetailTableBody = document.getElementById('salesDetailTableBody');
              saleDetailTableBody.innerHTML = ''; // Clear any existing rows

              let totalAmount = 0;
              data.saleDetails.forEach(detail => {
                  // Pastikan total_price adalah number
                  const pricePerItem = Number(detail.total_price) / Number(detail.quantity_product);
                  const subtotal = Number(detail.total_price);

                  if (!isNaN(subtotal)) {
                      totalAmount += subtotal;
                  }

                  let row = document.createElement('tr');
                  row.innerHTML = `
                      <td class="px-6 py-3 text-left text-sm text-gray-500">${detail.product.name}</td>
                      <td class="px-6 py-3 text-left text-sm text-gray-500">${detail.quantity_product}</td>
                      <td class="px-6 py-3 text-left text-sm text-gray-500">${formatRupiah(pricePerItem)}</td>
                      <td class="px-6 py-3 text-left text-sm text-gray-500">${formatRupiah(subtotal)}</td>
                  `;
                  saleDetailTableBody.appendChild(row);
              });

              // Update total amount dengan sub_total dari response (sudah dikurangi point)
              document.getElementById('totalAmount').textContent = formatRupiah(data.sub_total);

              // Created at and by
              document.getElementById('createdAt').textContent = `Dibuat pada tanggal: ${data.created_at}`;
              document.getElementById('createdBy').textContent = `Oleh: ${data.created_by}`;
          })
          .catch(error => {
              console.error('Error fetching sale details:', error);
          });
    }

    // Format number to Rupiah currency
    function formatRupiah(amount) {
      // Pastikan amount adalah number yang valid
      const num = Number(amount);
      if (isNaN(num)) {
          return 'Rp 0';
      }
      return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    // Close modal
    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('salesDetailModal').classList.add('hidden');
    });

    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('salesDetailModal').classList.add('hidden');
    });
</script>

 {{-- Script untuk Sales --}}
 <script>
    function initializeSalesPagination() {
        // Search on keyup with delay
        let salesSearchTimer;
        $('#salesSearchInput').on('keyup', function() {
            clearTimeout(salesSearchTimer);
            salesSearchTimer = setTimeout(function() {
                loadSalesData();
            }, 500);
        });

        // Change per page
        $('#salesPerPage').on('change', function() {
            loadSalesData();
        });

        // Handle pagination clicks
        $(document).on('click', '.sales-pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadSalesData(page);
        });

        function loadSalesData(page = 1) {
            const search = $('#salesSearchInput').val();
            const perPage = $('#salesPerPage').val();

            $.ajax({
                url: '{{ route("sales.index") }}',
                type: 'GET',
                data: {
                    search: search,
                    per_page: perPage,
                    page: page,
                    ajax: true
                },
                success: function(response) {
                    $('#salesTableContainer').html(response.html);
                    $('#salesPaginationLinks').html(response.pagination);
                    if (response.entries_info) {
                        $('#salesEntriesInfo').text(response.entries_info);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    $(document).ready(function() {
        initializeSalesPagination();
    });
</script>


