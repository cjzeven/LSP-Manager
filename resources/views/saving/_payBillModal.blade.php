<!-- Modal -->
<div class="modal fade" id="payBillModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pay Bill: xxx</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="modal-item">
                        <div>
                            <h5>Payment History</h5>
                                <div class="form-group">
                                    <label>Test &nbsp;
                                        <button type="button" class="close" aria-label="Close" >
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="IDR">
                                        <input type="text" aria-label="Date" class="form-control" placeholder="Date">
                                        <div class="input-group-append append-items">
                                            <span class="input-group-text fileupload-container">
                                                <input type="file" name="uploadReceipt">&nbsp;&nbsp;
                                                <a href="#" target="_blank">Receipt</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <button class="btn btn-danger" @click="doHandlePayment">Add Payment</button>
                        </div>
                    </div>

                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p class="inline">Target Budget: <strong>11111</strong></p>|
                <p class="inline">Total Spent: <strong>22222</strong></p>|
                <p class="inline">Budget Left: <strong>33333</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
