<!-- Modal -->
<div class="modal fade" id="planDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Details: @{{ detailsData.datetime }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="modal-item">
                        <h4>Required Items</h4>
                        <ul class="list-group list-group-flush" v-if="detailsData.requiredItems.length">
                            <li class="list-group-item" v-for="item in detailsData.requiredItems">
                                <div class="row">
                                    <div class="col-sm">@{{ item.name }}</div>
                                    <div class="col-sm">@{{ _format(item.amount) }}</div>
                                    <div class="col-sm">
                                        <a v-if="item.receipt_photo" :href="item.receipt_photo" target="_blank">Receipt</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul v-else>
                            <li>No items yet</li>
                        </ul>
                    </div>
                    <hr>
                    <div class="modal-item">
                        <h4>Regular Items</h4>
                        <ul class="list-group list-group-flush" v-if="detailsData.regularItems.length">
                            <li class="list-group-item" v-for="item in detailsData.regularItems">
                                <div class="row">
                                    <div class="col-sm">@{{ item.name }}</div>
                                    <div class="col-sm">@{{ _format(item.amount) }}</div>
                                    <div class="col-sm">
                                        <a v-if="item.receipt_photo" :href="item.receipt_photo" target="_blank">Receipt</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul>
                            <li>No items yet</li>
                        </ul>
                    </div>
                    <hr>
                    <div class="modal-item">
                        <h4>Summary</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm">Total</div>
                                    <div class="col-sm">@{{ _format(detailsData.summary) }}</div>
                                    <div class="col-sm"></div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="handleGeneratePDF(detailsData.id)">Generate PDF</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
