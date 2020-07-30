<!-- Modal -->
<div class="modal fade" id="payBillModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pay Bill: @{{ currentModalData.datetime }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="modal-item">
                        <div>
                            <h5>Target Budget</h5>
                            <input type="text" class="form-control" placeholder="IDR" v-model="currentModalData.targetBudget">
                        </div>
                        <hr>
                        <div>
                            <h5>Required Items</h5>
                            <transition-group name="fade" tag="div">
                                <div class="form-group fade-item" v-for="item in payBillForm.requiredItems" v-bind:key="item.id">
                                    <label>@{{ item.name }} &nbsp;
                                        <button v-if="!item.paid" type="button" class="close" aria-label="Close" v-on:click="handlePayBillRemoveItem(item.id)">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="IDR" v-model="item.amount" :disabled="item.paid" @keyup="handlePayBillAmountKeyup($event, item.id)">
                                        <div class="input-group-append append-items">
                                            <span class="input-group-text">
                                                <input type="checkbox" name="paid" @change="handlePaid(item.id)" :checked="item.paid">&nbsp;Paid
                                            </span>
                                            <span class="input-group-text fileupload-container">
                                                <input type="file" name="uploadReceipt" @change="handleUploadReceipt($event, item.id)">&nbsp;&nbsp;
                                                <a v-if="item.receiptPhoto" :href="item.receiptPhoto" target="_blank">Receipt</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>
                            <button class="btn btn-danger" v-on:click="handlePayBillAddRequiredItem">Add Required Item</button>
                        </div>
                        <br>
                        <hr>

                        <div>
                            <h5>Regular Items</h5>
                            <transition-group name="fade" tag="div">
                                <div class="form-group fade-item" v-for="item in payBillForm.regularItems" v-bind:key="item.id">
                                    <label>@{{ item.name }} &nbsp;
                                        <button v-if="!item.paid" type="button" class="close" aria-label="Close" v-on:click="handleRemoveRegularItem(item.id)">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="IDR" v-model="item.amount" :disabled="item.paid" @keyup="handlePayBillAmountKeyup($event, item.id)">
                                        <div class="input-group-append append-items">
                                            <span class="input-group-text">
                                                <input type="checkbox" name="paid" @change="handlePaidRegularItem(item.id)" :checked="item.paid">&nbsp;Paid
                                            </span>
                                            <span class="input-group-text fileupload-container">
                                                <input type="file" name="uploadReceipt" @change="handleUploadReceipt($event, item.id)">&nbsp;&nbsp;
                                                <a v-if="item.receiptPhoto" :href="item.receiptPhoto" target="_blank">Receipt</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>
                            <button class="btn btn-success" v-on:click="handleAddRegularItem">Add Regular Item</button>
                        </div>
                    </div>

                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p class="inline">Target Budget: <strong>@{{ _format(currentModalData.targetBudget) }}</strong></p>|
                <p class="inline">Total Spent: <strong>@{{ _format(calculateTotalSpent) }}</strong></p>|
                <p class="inline">Budget Left: <strong>@{{ _format(calculateBudgetLeft) }}</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
