<div class="modal fade bs-example-modal-center" id="add-note-modal" tabindex="-1" role="dialog" aria-labelledby="Catatan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 align-items-center" id="mySmallModalLabel">Tambah Catatan Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id" value="">
            <input type="hidden" id="route" value="">
            <div class="modal-body">
                <form action="{{route('admin.member.store')}}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="type" value="customerNote">
                    <input id="pid" type="hidden" class="form-control" name="pid" value="{{ old('pid') }}">
                    <input id="captureFile" type="hidden" name="captureFile" value="">
                    <div class="form-group">
                        <div class="card card-body col-6">
                            <label for="pid" class="text-center">Capture</label>
                            <img class="img-fluid mx-auto d-block rounded" src="{{asset('assets/images/head-default.png')}}" id="capture" alt="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">No Handphone</label>
                        <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="081xxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label for="notes">Catatan Customer</label>
                        <!-- <input type="text" class="form-control" placeholder="Kopi 1, Teh 1, ..." name="notes" value="{{old('notes')}}" required> -->
                        <textarea id="notes" class="form-control" rows="5"  placeholder="Kopi 1, Teh 1, ..." name="notes" value="{{old('notes')}}" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="addNotecustomer()">Tambah Catatan</button> -->
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit">Tambah Catatan</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    // function addNotecustomer() {
        // var pid = $('#pid').val();
        // var route = "/member/store";
        // $.ajax({
            // url: route,
            // headers: {
                // 'X-CSRF-TOKEN': '{{csrf_token()}}'
            // },
            // data: {
                // 'pid': pid,
                // 'type': 'customerNote',
            // },
            // method: "POST",
            // dataType: "JSON",
            // success: function (data) {
                // location.reload();
            // }
        // });
    // }
</script>
