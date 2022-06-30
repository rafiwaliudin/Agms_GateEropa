@extends('pages.app-agms.index')
@section('content')
    <h4 class="home">HOME</h4>
    @if($vehicles->count()) 
    @foreach($vehicles as $vehicle)
        <div class="card margin-card">
            <div class="card-body">
                <p class="card-text">Vehicle Name : {{$vehicle->car_type}}</p>
                <p class="card-text">Vehicle Name : {{$vehicle->license_plate}}</p>
                @if($vehicle->qrCodeVehicle !== null)
                    <button id="modalQr" type="button" class="btn btn-info btn-block btn-sm waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#myModal" data-qr="{{$vehicle->qrCodeVehicle->qrcode_image_path}}">Show QrCode
                    </button>
                @else
                    <button id="modalQr" type="button" class="btn btn-info btn-block btn-sm waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#myModal" data-qr="{{asset('assets/images/qr-code.png')}}">Show QrCode
                    </button>
                @endif
            </div>
        </div>
    @endforeach       
                
    @else
    <div class="margin-vehicle"></div> 
    @endif
    
    {{ $vehicles->links() }}
    <div class="space"></div>
    

    <!-- modal qrcode -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">QRCode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <img id="imgQr" class="qr-modal" src="" style="height: 200px; width: 200px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-block waves-effect" data-dismiss="modal">Close
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script>
        $(document).on("click", "#modalQr", function () {
            var parseQr = $('#imgQr').attr('src', $(this).data('qr'));
        });
    </script>
@endsection
