<div class="modal fade bs-example-modal-center" id="verification-modal" tabindex="-1" role="dialog"
     aria-labelledby="Verification"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 align-items-center" id="mySmallModalLabel">Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id" value="">
            <input type="hidden" id="route" value="">
            <div class="modal-body">
                <h4 class="text-center">Are you sure you want to delete this data ?</h4>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="deleteData()">Yes</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    function deleteData() {
        var id = $('#id').val();
        var route = $('#route').val();
        $.ajax({
            url: route,
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
