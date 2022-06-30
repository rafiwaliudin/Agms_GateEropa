@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Buat Tamu Baru</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat member
                        baru</p>
                    @include('flash::message')
                    <div class="row">
                        <div class="card-deck-wrapper col-6">
                            <div class="card-deck">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title text-center">Upload Your ID Card</h4>
                                        <form action="" id="formAddMemberWithIdCard" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="{{$type}}">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input class="form-control" type="tel" name="phone" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="idCard">ID Card</label>
                                                <input class="form-control" type="file" name="idCard"required>
                                            </div>

                                            <button type="submit" class="btn btn-primary waves-effect waves-light float-right" id="submit">Send File
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-deck-wrapper col-6">
                            <div class="card-deck">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title text-center">Data Detail
                                            Profile</h4>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <td id="name">----------------------------------------</td>
                                                </tr>
                                                <tr>
                                                    <th>NIK</th>
                                                    <td id="id-card-number">----------------------------------------
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Lahir</th>
                                                    <td id="pob">----------------------------------------</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Lahir</th>
                                                    <td id="dob">----------------------------------------</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
@endsection

