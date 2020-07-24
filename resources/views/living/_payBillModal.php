<!-- Modal -->
<div class="modal fade" id="payBillModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pay Bill: xxxx/xx/xx</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="modal-item" v-if="createPlanForm.plan == 'new'">                        
                        <div>
                            <h5>Required Items</h5>
                            <div class="form-group" v-for="item in createPlanForm.requiredItems">
                                <label>{{ item.name }} &nbsp;
                                    <button type="button" class="close" aria-label="Close" v-on:click="handleRemoveItem(item.id)">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </label>
                                <input type="text" class="form-control" placeholder="IDR" v-model="item.amount">
                            </div>
                            <button class="btn btn-danger" v-on:click="handleAddRequiredItem">Add Required Item</button>
                        </div>
                        <br>
                        <hr>

                        <div>
                            <h5>Regular Items</h5>
                            <div class="form-group" v-for="item in payBillForm.regularItems">
                                <label>{{ item.name }} &nbsp;
                                    <button type="button" class="close" aria-label="Close" v-on:click="handleRemoveRegularItem(item.id)">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </label>
                                <input type="text" class="form-control" placeholder="IDR" v-model="item.amount">
                            </div>
                            <button class="btn btn-success" v-on:click="handleAddRegularItem">Add Regular Item</button>
                        </div>
                    </div>

                    <div class="modal-item" v-if="createPlanForm.plan == 'existing'">
                        <div>
                            <h5>Select Month</h5>
                            <select name="plan" class="form-control">
                                <option value="1">June - 2020</option>
                                <option value="2">Mei - 2020</option>
                                <option value="2">April - 2020</option>
                            </select>
                        </div>
                    </div>

                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p class="inline">Target Budget: <strong>IDR {{ (createPlanForm.targetBudget - calculateRequiredItemTotal) }}</strong></p>|
                <p class="inline">Total Spent: <strong>IDR {{ (createPlanForm.targetBudget - calculateRequiredItemTotal) }}</strong></p>|
                <p class="inline">Budget Left: <strong>IDR {{ (createPlanForm.targetBudget - calculateRequiredItemTotal) }}</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>