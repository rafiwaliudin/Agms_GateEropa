@extends('pages.post-qr.index')
@section('content')
    <form action="{{route('app.store')}}" method="POST" class="form-regist">
        @csrf
        @include('flash::message')
        <div class="form-group row mt-5">
            <div class="col-sm-12">
                <label class="label" for="name">Nama</label>
                <input type="text" class="form-control" name="name" placeholder="Nama">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label class="label" for="license_plate">Plat Nomor</label>
                <input type="text" class="form-control" name="license_plate" placeholder="Masukan plat nomor anda">
            </div>
        </div>
        <div class="form-group row form-button">
            <div class="col-sm-12 ">
                <button type="submit" class="btn btn-primary btn-lg btn-block" id="buttonCreate">Submit</button>
            </div>
        </div>
    </form>
@endsection
