<!-- Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <label>
                            <input type="radio" value="new" name="plan" v-model="createPlanForm.plan">
                            &nbsp;New
                        </label>
                        <label class="ml-3">
                            <input type="radio" value="existing" name="plan" v-model="createPlanForm.plan">
                            &nbsp;Use Existing Data
                        </label>
                    </div>
                    <br>
                    <transition name="slide-fade">
                        <div class="modal-item" v-show="createPlanForm.plan == 'new'">
                            <div>
                                <h5>Date</h5>
                                <div class="form-group">
                                    <input id="createPlanDateTime" type="text" class="form-control" placeholder="yyyy/mm/dd">
                                </div>
                            </div>

                            <div>
                                <h5>Target Budget</h5>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="IDR" v-model="createPlanForm.targetBudget">
                                </div>
                            </div>

                            <div>
                                <h5>Required Items</h5>
                                <transition-group name="fade" tag="div">
                                    <div class="form-group fade-item" v-for="item in createPlanForm.requiredItems" v-bind:key="item.id">
                                        <label>@{{ item.name }} &nbsp;
                                            <button type="button" class="close" aria-label="Close" v-on:click="handleRemoveItem(item.id)">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </label>
                                        <input type="text" class="form-control" placeholder="IDR" v-model="item.amount">
                                    </div>
                                </transition-group>
                                <button class="btn btn-danger" v-on:click="handleAddRequiredItem">Add Item</button>
                            </div>
                        </div>
                    </transition>

                    <transition name="slide-fadeout">
                        <div class="modal-item" v-show="createPlanForm.plan == 'existing'">
                            <div>
                                <h5>Select Month</h5>
                                <select name="plan" class="form-control">
                                    <option value="1">June - 2020</option>
                                    <option value="2">Mei - 2020</option>
                                    <option value="2">April - 2020</option>
                                </select>
                            </div>
                        </div>
                    </transition>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <p>Total Items: <strong>IDR @{{ calculateRequiredItemTotal }}</strong></p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" v-on:click="handleCreatePlanCreate">Create</button>
            </div>
        </div>
    </div>
</div>
