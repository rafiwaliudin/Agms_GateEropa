@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Ubah Data Pengunjung</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data
                        pengunjung</p>

                    <form action="{{route('admin.visitor.update', $visitor->id)}}" method="POST"
                          class="needs-validation" novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="Nama" name="name"
                                   value="{{$visitor->name}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="license_plate">Plat Nomor</label>
                            <input type="text"
                                   class="form-control @if ($errors->has('license_plate')) is-invalid @endif"
                                   placeholder="Plat Nomor" name="license_plate"
                                   value="{{$visitor->license_plate}}">
                            @if ($errors->has('license_plate'))
                                <span class="text-danger">
                                        {{ $errors->first('license_plate') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="qrcode_expiry_date">QR Code Expired Date</label>
                            <input type="datetime-local"
                                   class="form-control @if ($errors->has('qrcode_expiry_date')) is-invalid @endif" name="qrcode_expiry_date"
                                   value="{{strftime('%Y-%m-%dT%H:%M:%S', strtotime($visitor->qrcode_expiry_date))}}" required>
                            @if ($errors->has('qrcode_expiry_date'))
                                <span class="text-danger">
                                        {{ $errors->first('qrcode_expiry_date') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="additional_information">Additional Information</label>
                            <input type="text"
                                   class="form-control @if ($errors->has('additional_information')) is-invalid @endif" name="additional_information"
                                   value="{{$visitor->additional_information}}">
                            @if ($errors->has('additional_information'))
                                <span class="text-danger">
                                        {{ $errors->first('additional_information') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="cluster">Cluster</label>
                            <select class="custom-select form-control selectpicker @if ($errors->has('cluster')) is-invalid @endif" multiple data-live-search="true" name="cluster[]" id="multipleSelect" required>
                                @foreach($clusters as $cluster)
                                <option value="{{$cluster->id}}" @if($visitor->clusters()->find($cluster->id)) selected @endif>
                                    {{$cluster->name}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('cluster'))
                                <span class="text-danger">
                                        {{ $errors->first('cluster') }}
                                    </span>
                            @endif
                        </div>
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
