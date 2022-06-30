@extends('pages.post-qr.index')
@section('content')
    <div>
        <a href="http://159.89.206.10:3005/api/v1/agms/open-gate?occupant_id=1" id="gate1" class="btn btn-primary btn-lg btn-block btn-index" style="margin-top: 30%; margin-bottom: 5%;">Open Gate Penghuni Uray</a>
        <a href="http://159.89.206.10:3005/api/v1/agms/open-gate?occupant_id=2" id="gate2" class="btn btn-primary btn-lg btn-block btn-index" style="margin-top: 5%; margin-bottom: 5%;">Open Gate Penghuni Fadhil</a>
        <!-- <button onclick="openGate(1)" id="gate1" class="btn btn-primary btn-lg btn-block btn-index">Open Gate Tamu</button>
        <button onclick="openGate(2)" id="gate2" class="btn btn-primary btn-lg btn-block btn-gate-2 ">Open Gate Resident</button> -->
        <a href="{{route('opengatetamu')}}" id="gate-tamu" class="btn btn-primary btn-lg btn-block btn-index" style="margin-top: 5%; margin-bottom: 5%;">Open Gate Tamu</a>
        <a href="{{route('opengateresident')}}" id="gate-resident" class="btn btn-primary btn-lg btn-block btn-index" style="margin-top: 5%; margin-bottom: 30%;">Open Gate Penghuni</a> 
    </div>
@stop
@section('js')
    <script>
        function openGate(id) {
            var idButton = '#gate' + id;
            $(idButton).attr("disabled", true);
            $(idButton).html("Please Wait");

            $.ajax({
                url: "{{route('app.open')}}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: {
                    'id': id,
                },
                dataType: 'JSON',
                success:
                    function (data) {
                        $(idButton).attr("disabled", false);
                        $(idButton).html(data.data);
                    }
            })
        }
    </script>

@endsection
