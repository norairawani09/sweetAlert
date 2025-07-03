@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Produk</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

    <table class="table table-bordered" id="products-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody> {{-- Body akan diisi oleh DataTables secara AJAX --}}
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('products.index') !!}', // URL untuk mengambil data produk
            columns: [
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'stock', name: 'stock' },
                // Pastikan nama kolom 'action' sama dengan yang dikembalikan controller
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Gunakan event delegation untuk tombol delete produk
        $('#products-table').on('click', '.delete-btn-product', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            confirmDeleteProduct(productId);
        });
    });

    // Fungsi JavaScript konfirmasi delete untuk produk (menggunakan SweetAlert2)
    function confirmDeleteProduct(productId) {
        Swal.fire({
            title: "Anda Yakin?",
            text: "Setelah dihapus, Anda tidak akan bisa mengembalikan produk ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = `/products/${productId}`; // Pastikan URL sesuai
                form.method = 'POST';

                let hiddenMethod = document.createElement('input');
                hiddenMethod.setAttribute('type', 'hidden');
                hiddenMethod.setAttribute('name', '_method');
                hiddenMethod.setAttribute('value', 'DELETE');
                form.appendChild(hiddenMethod);

                let csrfToken = document.createElement('input');
                csrfToken.setAttribute('type', 'hidden');
                csrfToken.setAttribute('name', '_token');
                csrfToken.setAttribute('value', '{{ csrf_token() }}');
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection