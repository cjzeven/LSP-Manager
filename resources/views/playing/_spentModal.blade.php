<!-- Modal -->
<div class="modal fade" id="spentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>@{{ spentForm.name }}</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="modal-item">
                            <div>
                                <h5>Payment History</h5>
                                <table class="table">
                                    <thead>
                                        <th>ID</th>
                                        <th>DateTime</th>
                                        <th>Amount</th>
                                        <th>Receipt Photo</th>
                                        <th>Options</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in spentForm.playingItems">
                                            <td>@{{ item.id }}</td>
                                            <td>@{{ item.datetime }}</td>
                                            <td>@{{ item.amount }}</td>
                                            <td>
                                                <a :href="item.receipt_photo" target="_blank" v-if="item.receipt_photo">Receipt</a>
                                                <a v-else>-</a>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <button class="btn btn-outline-danger btn-sm" @click="handleSpentDelete(item.id)">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="spentForm.playingItems.length <= 0">
                                            <td colspan="5">
                                                <p class="text-center">No payments yet</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
    
                            <hr>
                            
                            <div>
                                <h5>Add Payment</h5>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="IDR" v-model="spentForm.payment.amount">
                                            <input type="text" aria-label="Date" class="form-control" placeholder="Date" id="createSpentDatepicker">
                                            <div class="input-group-append append-items">
                                                <span class="input-group-text fileupload-container">
                                                    <input type="file" name="uploadReceipt" id="addPaymentUpload">&nbsp;&nbsp;
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <button class="btn btn-danger btn-sm" @click="handleSpentPay">Pay</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p class="inline">Target Budget: <strong>111</strong></p>|
                <p class="inline">Total Spent: <strong>222</strong></p>|
                <p class="inline">Budget Left: <strong>333</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
