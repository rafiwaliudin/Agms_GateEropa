@extends('pages.app-agms.index')
@section('content')

    <h4 class="home">Edit Visitor</h4>

    <div class="description-form"> Ubah data pada form dibawah ini untuk mengupdate,</div>
    <div class="description-form"> data Visitor, dan klik submit</div>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="p-2">
                        <form method="post" action="{{route('app.updateVisitor',  $visitor->id)}}"
                              class="form-horizontal">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="name">Name</label>
                                        <input id="name" type="text" placeholder="Masukan nama tamu anda"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ $visitor->name }}" autofocus>

                                        @error('name')
                                        <span role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="license_plate">License plate</label>
                                        <input id="license_plate" type="text" placeholder="Masukan plat nomor kendaraan"
                                               class="form-control @error('license_plate') is-invalid @enderror"
                                               name="license_plate" value="{{$visitor->license_plate}}">
                                        @error('license_plate')
                                        <span role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-info btn-block waves-effect waves-light"
                                                type="submit">Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="space-form"></div>
@endsection


