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
                    <div class="modal-item">
                        <div>
                            <h5>Name</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name" v-model="createPlanForm.name">
                            </div>
                        </div>

                        <div>
                            <h5>Target Budget</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="IDR" v-model="createPlanForm.target_budget">
                            </div>
                        </div>

                        <div>
                            <h5>Date</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Date" id="createPlanDatepicker">
                            </div>
                        </div>
                    </div>
                </div>

                <br>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="doHandleCreatePlan">Create</button>
            </div>
        </div>
    </div>
</div>
