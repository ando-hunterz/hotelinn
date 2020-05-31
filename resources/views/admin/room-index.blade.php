@extends('layouts.admin')

@section('item')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
<div class="container mt-4 mx-4">
    <div class="col-12">
        <h2 class="mt-3">Daftar Kamar Hotel</h2><br>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @elseif (session('unstatus'))
            <div class="alert alert-danger">
                {{ session('unstatus') }}
            </div>
        @endif
        <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Ruangan</th>
                    <th>Kamar Kosong</th>
                    <th>Kapasitas</th>
                    <th>Harga</th>
                    <th>Panel</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($room_details as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->available }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->cost }}</td>
                    <td>
                        <a href="/admin/rooms/{{ $room->id }}" class="mx-2 badge badge-info">Cek Kamar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
        } );
    } );
</script>
@endsection