<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #fff">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ARE YOU SURE <strong><?php echo strtoupper($nome); ?></strong> YOU WANT TO EXIT?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "LOGOUT" to disconnect your account!</div>
            <div class="modal-footer">
                <button class="btn btn-info lift lift-sm" type="button" data-dismiss="modal">CANCEL</button>
                <a class="btn btn-primary lift lift-sm" href="php/logout.php">LOGOUT</a>
            </div>
        </div>
    </div>
</div>
