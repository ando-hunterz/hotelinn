@extends('layouts.app')

@section('content')
@if (session('success'))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success my-5">
                {{ session('success') }}
                Klik <a href="/history"
                        onclick="event.preventDefault();
                                document.getElementById('history-form').submit();">di sini</a>
                untuk menuju ke Riwayat Pemesanan Anda.
            </div>
            <form id="history-form" action="/history" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
@endif
<div class="slider fade">
    <div class="load"></div>
    <div class="content">
        @if (session('success'))
        <div class="principal" style="top: 60%;">
        @else
        <div class="principal" style="top: 40%;">
        @endif
            <h1>Bingung mau nginep di mana?<br><b>hotelinn</b> aja.</h1>
        </div>
    </div>
</div>
<div class="bg-lightblue">
    <div class="container">
        <div class="row justify-content-center" id="searchRow">
            <div class="col-md-8 my-5">
                <form>
                    <div class="row justify-content-center">
                        <div class="col">
                            <select class="form-control" name="provinsiId" id="provinsi">
                                <option value="all" selected> Pilih provinsi </option>
                                @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}"> {{ $provinsi->namaProvinsi }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" name="kotaId" id="kota">
                                <option value="null"> Pilih kota </option>
                            </select>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary" id="search">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">

    <div class='row justify-content-center' >
        <div class='col-md-8' id='hotel-row'>
            @foreach($hotels as $hotel)
            <div class='card my-5 card-hotel'>
                <div class='card-header'>{{ $hotel->hotel->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <p class="mx-3">
                            {{$hotel->detailLengkap}}
                        </p>
                    </div>
                    <div class="row justify-content-center">
                        <form method="get" action="/showRoom">
                            @csrf
                        <input type="hidden" id="hotelId" name="hotelId" value="{{$hotel->hotel->id}}">
                        <button type="submit" class="btn btn-primary">Show
                            Room</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<script>
    $( document ).ready(function() {
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
        });
        $.ajax({
            type: 'POST',
            url: '/getKota',
            data: { provinsi: 'all'},
            success: function(result) {
                var result = JSON.parse(result);
                console.log(result);
                result.forEach(element => {

                    $( "#kota" ).append('<option value=' + element.id + '>' + element.namaKota + '</option>');

                });
            }
        });
        $( "#provinsi" ).change(function() {
        var provinsi = $('#provinsi').val()
        console.log(provinsi);
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
        });
        $.ajax({
            type: 'POST',
            url: '/getKota',
            data: { provinsi: provinsi },
            success: function(result) {
                var result = JSON.parse(result);
                console.log(result);
                $( "#kota option" ).remove();
                $( '#kota').append( '<option value="null"> Pilih... </option>')
                result.forEach(element => {

                    $( "#kota" ).append('<option value=' + element.id + '>' + element.namaKota + '</option>');

                });
            }
        });
        });
        $( '#search' ).on('click', function (){
            var provinsiId = $('#provinsi').val();
            var kotaId = $("#kota").val();
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
            });
            $.ajax({
                type: 'GET',
                url: '/getHotel',
                data: { provinsiId: provinsiId, kotaId: kotaId},
                success: (result) => {
                    result = JSON.parse(result);
                    $( '.card-hotel').remove();
                    result.forEach(hotel => {
                        $('#hotel-row').append(
                              "<div class='card my-5 card-hotel'>" +
                              "<div class='card-header'>"+hotel.name+"</div>"+
                              '<div class="card-body"><div class="row">'+
                              '<p class="mx-3">'+hotel.detailLengkap+'</p></div>' +
                              '<div class="row justify-content-center">'+
                              '<form method="get" action="/showRoom">@csrf'+
                              '<input type="hidden" id="hotelId" name="hotelId" value="'+hotel.id+'">'+
                              '<button type="submit" class="btn btn-primary">ShowRoom</button>'+
                              '</form></div></div></div></div></div>');
                    })

                }
            })
        });
    });

</script>
@endsection
