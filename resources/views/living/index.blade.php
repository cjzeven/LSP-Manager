@extends('layouts.app')

@section('content')

<style>
    .modal-item {
        margin-left: -15px;
        margin-right: -15px;
    }
    .fileupload-container {
        width: 350px !important;
    }
</style>

<div class="container" id="app">
    <div>
        <button class="btn btn-success btn-sm" v-on:click="handleCreatePlan">Create Plan</button>
    </div>

    <br>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Date Time</th>
                <th>Target Budget</th>
                <th>Total Spent</th>
                <th>Budget Left</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="livingData.length > 0" v-for="data in livingData">
                <td>@{{ data.id }}</td>
                <td>@{{ data.datetime }}</td>
                <td>IDR @{{ data.target_budget }}</td>
                <td>IDR @{{ data.total_spent }}</td>
                <td>IDR @{{ (data.target_budget - data.total_spent) }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-danger" @click="handlePayBill(data.id)">Pay Bill</button>
                        <button type="button" class="btn btn-sm btn btn-outline-success" @click="handlePlanDetails(data.id)">Details</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="handleDeletePlan(data.id)">Delete</button>
                    </div>
                </td>
            </tr>
            <tr v-if="livingData.length <= 0">
                <td colspan="6"><p class="text-center">No items</p></td>
            </tr>
        </tbody>
    </table>

    @include('living._createPlanModal')

    @include('living._payBillModal')

    @include('living._planDetailsModal')

</div>

<script type="text/babel">
    const vueObj = new Vue({
        el: '#app',
        data: {
            createPlanForm: {
                plan: 'new',
                dateTime: '',
                requiredItems: [],
                targetBudget: 0,
            },
            payBillForm: {
                requiredItems: [],
                regularItems: [],
                targetBudget: 0,
            },
            livingData: [],
            detailsData: [],
            currentLivingStateId: 0,
        },
        methods: {
            handleCreatePlan() {
                $('#createPlanModal').modal();
            },
            handleAddRequiredItem() {
                const name = prompt('Item Name');

                if (name.length > 0) {
                    const newItem = {
                        id: String(Math.random()),
                        name: name,
                        amount: 0,
                        paid: 0,
                        receiptPhoto: '',
                    }
                    this.createPlanForm.requiredItems = [...this.createPlanForm.requiredItems, newItem];
                }
            },
            handleRemoveItem(id) {
                this.createPlanForm.requiredItems = this.createPlanForm.requiredItems.filter(item => item.id != id);
            },
            async handleCreatePlanCreate() {
                try {
                    const create = await axios.post('{{ url("api/living/create") }}', {
                        requiredItems: [...this.createPlanForm.requiredItems],
                        targetBudget: this.createPlanForm.targetBudget,
                        datetime: this.createPlanForm.dateTime,
                    });

                    if (create.status === 200) {
                        this.livingData = (await axios.get('api/living/all')).data;
                        $('#createPlanModal').modal('hide');
                    }
                } catch (error) {
                    console.log('ERR handleCreatePlanCreate', error);
                }
            },
            async handlePayBill(id) {
                // TODO: mengambil detail dari plan berdasarkan id
                try {
                    const living = await axios.get('{{ url("api/living") }}/' + id);

                    if (living.status === 200) {
                        const requiredItems = [];
                        const regularItems = [];

                        for (let item of living.data) {
                            if (item.is_required) {
                                item.receiptPhoto = item.receipt_photo;
                                delete item.receipt_photo;
                                item.paid = !!item.paid;
                                requiredItems.push(item);
                            } else {
                                item.receiptPhoto = item.receipt_photo;
                                delete item.receipt_photo;
                                item.paid = !!item.paid;
                                regularItems.push(item);
                            }
                        }

                        this.payBillForm = {regularItems, requiredItems};
                        this.currentLivingStateId = id;

                        $('#payBillModal').modal();
                    }
                } catch (error) {
                    console.log('ERR handlePayBill', error);
                }
            },
            async handlePayBillRemoveItem(id) {
                try {
                    const item = await axios.get('{{ url("api/living/delete") }}/' + id);

                    if (item.status === 200) {
                        this.payBillForm.requiredItems = this.payBillForm.requiredItems.filter(item => item.id != id);
                    }
                } catch (error) {
                    console.log('ERR handlePayBillRemoveItem', error);
                }
            },
            handleRemoveRegularItem(id) {
                this.payBillForm.regularItems = this.payBillForm.regularItems.filter(item => item.id != id);
            },
            handleAddRegularItem() {
                const name = prompt('Item Name');

                if (name.length > 0) {
                    const newItem = {
                        id: String(Math.random()),
                        name: name,
                        amount: 0,
                        paid: false,
                        receiptPhoto: '',
                    }
                    this.payBillForm.regularItems = [...this.payBillForm.regularItems, newItem];
                }
            },
            handlePaidRegularItem(id) {
                // TODO: send axios to update related item

                // TODO: cari data berdasarkan id tersebut dan disable form inputnya
                this.payBillForm.regularItems = this.payBillForm.regularItems.map(item => {
                    if (item.id == id) {
                        item.paid = !item.paid;
                    }
                    return item;
                });
            },
            async handlePaid(id) {
                try {
                    const livingItem = await axios.post('{{ url("api/living/paid") }}/' + id);

                    this.payBillForm.requiredItems = this.payBillForm.requiredItems.map(item => {
                        if (item.id == livingItem.data.id) {
                            item.paid = !item.paid;
                        }
                        return item;
                    });
                } catch (error) {
                    console.log('ERR handlePaid', error);
                }

            },
            handleUploadReceipt(e, id) {
                const file = e.target.files[0];

                // TODO: kirim image ke backend

                // TODO: update receiptImage property berdasarkan id
                this.payBillForm.requiredItems = this.payBillForm.requiredItems.map(item => {
                    if (item.id == id) {
                        item.receiptPhoto = file.name;
                    }
                    return item;
                });

            },
            async handlePayBillAddRequiredItem() {
                const name = prompt('Item Name');

                const id = this.currentLivingStateId;

                try {
                    const item = await axios.post('{{ url("") }}' + '/api/living/'+ id +'/create', {
                        name: name,
                        amount: 0,
                        paid: 0,
                        isRequired: 1,
                        receiptPhoto: '',
                    });

                    const newData = {
                        id: item.data.id,
                        name: item.data.name,
                        amount: item.data.amount,
                        paid: !!item.data.paid,
                        isRequired: !!item.data.is_required,
                        receiptPhoto: item.data.receipt_photo,
                    };

                    if (name.length > 0) {
                        this.payBillForm.requiredItems = [...this.payBillForm.requiredItems, newData];
                    }
                } catch (error) {
                    console.log('ERR handlePayBillAddRequiredItem', error);
                }

            },
            handlePlanDetails(id) {
                // TODO: request plan details berdasarkan id

                // TODO: simpan di detailsData
                this.detailsData = [];

                // TODO: tampilkan modal plan details
                $('#planDetailsModal').modal();
            },
            handleDeletePlan(id) {
                if (confirm('Are you sure to DELETE this plan?')) {
                    this.livingData = this.livingData.filter(item => item.id != id);
                }
            },
            handlePayBillAmountKeyup: _.debounce(async function(e, id) {
                await axios.post('{{ url("") }}' + '/api/living/item/' + id, {
                    amount: e.target.value,
                });

            }, 1300),
        },
        async mounted() {
            
            this.livingData = (await axios.get('api/living/all')).data;

            $('#createPlanDateTime').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(),
            })
            .on('changeDate', (e) => {
                const date = moment(e.date).format('YYYY/MM/DD hh:mm:ss');
                this.createPlanForm.dateTime = date;
            });
        },
        updated() {
            
        },
        computed: {
            calculateRequiredItemTotal() {
                return this.createPlanForm.requiredItems.reduce((accumulator, item) => accumulator + parseFloat(item.amount), 0);
            }
        }
    });
</script>

@endsection