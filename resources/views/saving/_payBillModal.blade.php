<!-- Modal -->
<div class="modal fade" id="payBillModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>@{{ paybillData.living.name }}</strong></h5>
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
                                        <tr v-for="item in paybillData.items">
                                            <td>@{{ item.id }}</td>
                                            <td>@{{ _formatDate(item.datetime) }}</td>
                                            <td>@{{ _format(item.amount) }}</td>
                                            <td>
                                                <a v-if="item.receipt_photo" :href="item.receipt_photo" target="_blank">Receipt</a>
                                                <a v-else>-</a>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <button class="btn btn-outline-danger btn-sm" @click="handleRemovePayment(item.id)">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="paybillData.items.length <= 0">
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
                                            <input type="text" class="form-control" placeholder="IDR" v-model="paybillData.form.amount">
                                            <input type="text" aria-label="Date" class="form-control" placeholder="Date" v-model="paybillData.form.datetime" id="add-payment-date">
                                            <div class="input-group-append append-items">
                                                <span class="input-group-text fileupload-container">
                                                    <input type="file" name="uploadReceipt" id="addPaymentUpload">&nbsp;&nbsp;
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <button class="btn btn-danger btn-sm" @click="doHandlePayment(paybillData.living.id)">Pay</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p class="inline">Target Budget: <strong>@{{ _format(paybillData.living.target) }}</strong></p>|
                <p class="inline">Total Spent: <strong>@{{ _format(paybillTotalSpent) }}</strong></p>|
                <p class="inline">Budget Left: <strong>@{{ _format(paybillData.living.target - paybillTotalSpent) }}</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
