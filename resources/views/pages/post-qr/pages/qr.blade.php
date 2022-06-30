@extends('pages.post-qr.index')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="qrcode">
            <div class="text-center">
                @if($licensePlate)
                    <p class="text-white" style="font-size: 50px; font-weight: bold">{{$licensePlate}}</p>
                @else
                    <p class="text-white" style="font-size: 50px; font-weight: bold">PLAT NOMOR</p>
                @endif
                <img class="card-img" src="{{asset($qrCode)}}"
                     style="height: 133px;width: 133px">
            </div>
        </div>
    </div>
@endsection
