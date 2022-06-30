@extends('pages.app-agms.index')
@section('content')
    <a href="{{route('app.createVisitor')}}" type="button"
       class="btn btn-primary btn-block btn-sm waves-effect waves-light edit-vehicle title-botton">Add Visitor</a>
    <h4 class="home">List Of Visitor</h4>
    @if($visitors->count())
        @foreach($visitors as $visitor)
            <div class="card margin-card">
                <div class="flex-menu">
                    <a href="#" id="buttonDeleteVisitor" class="margin-trash" data-toggle="modal"
                       data-id="{{$visitor->id}}"
                       data-target="#modalDelete">
                        <i class="far fa-trash-alt fa-2x"> </i>
                    </a>
                    <a href="{{route('app.editVisitor',$visitor->id)}}">
                        <i class="fas fa-edit fa-2x"> </i>
                    </a>
                </div>
                <div class='card-title text-center'>{{$visitor->name}}</div>
                <img class="qr-visitor" src="{{$visitor->qrcode_image_path}}"/>
                <div class="card-body">
                    <p class="card-text">License Plate : {{$visitor->license_plate}}</p>
                    <a href="{{$visitor->qrcode_image_path}}" download="{{$visitor->qrcode_string}}" type="button"
                       class="btn btn-info btn-block btn-sm waves-effect waves-light">Download</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="margin-visitor"></div>
    @endif
    <div class="space-visitor"></div>
    {{ $visitors->links() }}

    <div id="modalDelete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalDeleteLabel">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <input type="hidden" id="idVisitor" value="">
                    <p> Apakah anda yakin ingin menghapus data tersebut ? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="deleteVisitor()">
                        Delete
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

        $(document).on("click", "#buttonDeleteVisitor", function () {
            var parseId = $('#idVisitor').val($(this).data('id'));
        });

        function deleteVisitor() {
            var id = $('#idVisitor').val();
            $.ajax({
                url: "/app/deleteVisitor",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: {
                    'id': id,
                },
                method: "POST",
                dataType: "JSON",
                success: function (data) {
                    location.reload();
                }
            });
        }
    </script>
@endsection
