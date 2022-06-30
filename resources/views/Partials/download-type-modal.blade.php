<div class="modal fade bs-example-modal-center" id="download-type-modal" tabindex="-1" role="dialog"
     aria-labelledby="Verification"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 align-items-center" id="mySmallModalLabel">Select Your Type File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-lg-6">
                        <button onclick="downloadReport('pdf')"
                                class="btn btn-danger text-white"><i class="mdi mdi-pdf-box"></i> PDF
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <button onclick="downloadReport('xls')" class="btn btn-success text-white"><i
                                class="mdi mdi-file-excel-box"></i> XLS
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Maybe Later</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
