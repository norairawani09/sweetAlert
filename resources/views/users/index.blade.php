@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Users</h1>

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah User</a>

    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#users-table').DataTable({ // Simpan instance DataTables ke variabel
            processing: true,
            serverSide: true,
            ajax: '{!! route('users.index') !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'level', name: 'level' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Gunakan event delegation untuk tombol delete
        // Ini akan melampirkan event listener ke elemen tabel (#users-table)
        // dan hanya akan memicu fungsi ketika klik terjadi pada elemen dengan class .delete-btn
        // yang ada di dalam tabel tersebut (termasuk yang ditambahkan secara dinamis).
        $('#users-table').on('click', '.delete-btn', function(e) { // Ubah selector dari button.btn-danger menjadi .delete-btn
            e.preventDefault(); // Mencegah perilaku default jika ada (misal, form submit)

            var userId = $(this).data('id'); // Ambil ID dari atribut data-id
            confirmDelete(userId);
        });
    });

    function confirmDelete(userId) {
        // UBAH DARI swal({ ... }) MENJADI Swal.fire({ ... })
        Swal.fire({ // Gunakan Swal.fire
            title: "Are you sure?",
            text: "Setelah dihapus, Anda tidak akan bisa mengembalikan pengguna ini!",
            icon: "warning",
            showCancelButton: true, // Untuk SweetAlert2
            confirmButtonColor: '#dc3545', // Warna tombol Delete
            cancelButtonColor: '#6c757d', // Warna tombol Cancel
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => { // Tangani hasilnya dengan 'result.isConfirmed'
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = `/users/${userId}`;
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