<div class="modal fade bs-example-modal-center" id="camera-type-modal" tabindex="-1" role="dialog"
     aria-labelledby="Verification"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 align-items-center" id="mySmallModalLabel">Select Your Type Camera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-lg-4">
                        <a href="{{route('admin.camera.create','recognize')}}" type="button"
                           class="btn btn-success text-white"><i class="mdi mdi-face-recognition"></i> Recognize</a>
                    </div>
                    <div class="col-lg-4">
                        <a href="{{route('admin.camera.create','counting')}}" type="button" class="btn btn-info text-white"><i
                                class="mdi mdi-counter"></i> Counting</a>
                    </div>
                    <div class="col-lg-4">
                        <a href="{{route('admin.camera.create','masking')}}" type="button" class="btn btn-primary text-white"><i
                                class="mdi mdi-transition-masked"></i> Masking</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Maybe Later</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
