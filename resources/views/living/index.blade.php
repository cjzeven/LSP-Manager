@extends('layouts.app')

@section('content')

<style>
    .modal-item {
        margin-left: -15px;
        margin-right: -15px;
    }
    .fileupload-container {
        width: 380px !important;
    }
    .fade-item {
        display: inline-block;
        width: 100%;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
    .fade-enter-to, .fade-leave {
        opacity: 1;
    }
    .fade-enter-active {
        transition: opacity .5s;
    }
    .fade-leave-active {
        transition: opacity .3s;
    }
    .slide-fade-enter-active {
      transition: all .3s ease;
    }
    .slide-fade-leave-active {
      transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to {
      transform: translateX(10px);
      opacity: 0;
    }
    .slide-fadeout-enter {
        opacity: 0;
        transform: translateX(10px);
    }
    .slide-fadeout-enter-to {
        opacity: 1;
    }
    .slide-fadeout-enter-active {
        transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
        transition-delay: .3s;
    }
    .append-items {
        height: 37px;
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
                <td>@{{ _format(data.target_budget) }}</td>
                <td>@{{ _format(data.total_spent) }}</td>
                <td>@{{ _format(data.target_budget - data.total_spent) }}</td>
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
            currentModalData: {},
            monthToDuplicate: [],
            selectedMonthToDuplicate: 0,
        },
        methods: {
            _format(value) {
                return moneyFormatIDR(value);
            },
            async getLivingData() {
                try {
                    const response = (await axios.get('api/living/all'));

                    if (response.status === 200) {
                        this.livingData = response.data;
                    }
                } catch (error) {
                    console.log('ERR getLivingData', error);
                }
            },
            async handleCreatePlan() {
                try {
                    const livingData = await axios.get('{{ url("api/living/all") }}');
                    const datetimes = livingData.data.map(item => {
                        return {datetime: item.datetime, id: item.id};
                    });
                    this.monthToDuplicate = datetimes;
                    this.selectedMonthToDuplicate = datetimes[0].id;
                } catch (error) {
                    console.log('ERR handleCreatePlan', error);
                }

                $('#createPlanModal').modal();
            },
            handleAddRequiredItem() {
                const name = prompt('Item Name');

                if (name) {
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
                        await this.getLivingData();
                        $('#createPlanModal').modal('hide');
                    }
                } catch (error) {
                    console.log('ERR handleCreatePlanCreate', error);
                }
            },
            async handleCreatePlanDuplicateDate() {
                const datetime = moment(new Date(document.getElementById('existingDataDatetime').value))
                                .format('YYYY/MM/DD hh:mm:ss');
                const id = this.selectedMonthToDuplicate;

                try {
                    const result = await axios.post('{{ url("") }}' + '/api/living/' + id + '/duplicate', {
                        datetime: datetime,
                    });
                    
                    if (result.status === 200) {
                        await this.getLivingData();
                        $('#createPlanModal').modal('hide');
                    }
                } catch (error) {
                    console.log('ERR handleCreatePlanDuplicateDate', error);
                }

            },
            async handlePayBill(id) {
                // TODO: mengambil detail dari plan berdasarkan id
                try {
                    const living = await axios.get('{{ url("api/living") }}/' + id);

                    if (living.status === 200) {
                        const requiredItems = [];
                        const regularItems = [];

                        this.currentModalData = {
                            id: living.data.id,
                            datetime: living.data.datetime,
                            targetBudget: living.data.target_budget,
                            totalSpent: living.data.total_spent,
                        };

                        for (let item of living.data.items) {
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
            async handleRemoveRegularItem(id) {
                // this.payBillForm.regularItems = this.payBillForm.regularItems.filter(item => item.id != id);
                try {
                    const item = await axios.get('{{ url("api/living/delete") }}/' + id);

                    if (item.status === 200) {
                        this.payBillForm.regularItems = this.payBillForm.regularItems.filter(item => item.id != id);
                    }
                } catch (error) {
                    console.log('ERR handlePayBillRemoveItem', error);
                }
            },
            async handleAddRegularItem() {
                const name = prompt('Item Name');
                const id = this.currentModalData.id;

                if (name) {
                    try {
                        const item = await axios.post('{{ url("") }}' + '/api/living/'+ id +'/create', {
                            name: name,
                            amount: 0,
                            paid: 0,
                            isRequired: 0,
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

                        this.payBillForm.regularItems = [...this.payBillForm.regularItems, newData];
                    } catch (error) {
                        console.log('ERR handleAddRegularItem', error);
                    }
                }
            },
            async handlePaidRegularItem(id) {
                try {
                    const livingItem = await axios.post('{{ url("api/living/paid") }}/' + id);

                    this.payBillForm.regularItems = this.payBillForm.regularItems.map(item => {
                        if (item.id == livingItem.data.id) {
                            item.paid = !item.paid;
                        }
                        return item;
                    });
                } catch (error) {
                    console.log('ERR handlePaidRegularItem', error);
                }
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
                const id = this.currentModalData.id;

                if (name) {
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

                        this.payBillForm.requiredItems = [...this.payBillForm.requiredItems, newData];
                    } catch (error) {
                        console.log('ERR handlePayBillAddRequiredItem', error);
                    }
                }
            },
            handlePlanDetails(id) {
                // TODO: request plan details berdasarkan id

                // TODO: simpan di detailsData
                this.detailsData = [];

                // TODO: tampilkan modal plan details
                $('#planDetailsModal').modal();
            },
            async handleDeletePlan(id) {
                if (confirm('Are you sure to DELETE this plan?')) {
                    try {
                        const item = await axios.get('{{ url("") }}' + '/api/living/'+ id +'/delete');

                        if (item.status === 200) {
                            this.livingData = this.livingData.filter(item => item.id != id);
                        }
                    } catch (error) {
                        console.log('ERR handleDeletePlan', error);
                    }
                }
            },
            handlePayBillAmountKeyup: _.debounce(async function(e, id) {
                await axios.post('{{ url("") }}' + '/api/living/item/' + id, {
                    amount: e.target.value,
                });

            }, 1300),
        },
        async mounted() {

            await this.getLivingData();

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

            $('#existingDataDatetime').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(),
            });
        },
        updated() {

        },
        computed: {
            calculateRequiredItemTotal() {
                return this.createPlanForm.requiredItems.reduce((acc, item) => acc + parseFloat(item.amount), 0);
            },
            calculateTotalSpent() {
                const totalSpentRequiredItems = this.payBillForm.requiredItems.reduce((acc, item) => acc + parseFloat(item.amount), 0);
                const totalSpentRegularItems = this.payBillForm.regularItems.reduce((acc, item) => acc + parseFloat(item.amount), 0);
                const totalSpent = totalSpentRequiredItems + totalSpentRegularItems;
                return totalSpent;
            },
            calculateBudgetLeft() {
                return this.currentModalData.targetBudget - this.calculateTotalSpent;
            }
        },
        watch: {
            // Watch target budget from updating
            // if theres updates, reflect to db too
            currentModalData: {
                handler: _.debounce(function (val) {
                    const targetBudget = val.targetBudget;
                    const id = this.currentModalData.id;
                    let _this = this;

                    axios.post('{{ url("") }}' + '/api/living/' + id + '/update', {
                        targetBudget: targetBudget,
                    })
                    .then(function(res) {
                        _this.livingData = _this.livingData.map(item => {
                            if (item.id == id) {
                                item.target_budget = targetBudget;
                            }
                            return item;
                        });
                    })
                    .catch(function(error) {
                        console.log('ERR currentModalData:handler', error);
                    });
                }, 1300),
                deep: true,
            },
            calculateTotalSpent: _.debounce(function(totalSpent) {
                // Watch total spent from updating
                // if theres updates, reflect to db too
                const id = this.currentModalData.id;
                let _this = this;

                axios.post('{{ url("") }}' + '/api/living/' + id + '/update', {
                    totalSpent: totalSpent,
                })
                .then(function(res) {
                    _this.livingData = _this.livingData.map(item => {
                        if (item.id == id) {
                            item.total_spent = totalSpent;
                        }
                        return item;
                    });
                })
                .catch(function(error) {
                    console.log('ERR calculateTotalSpent:handler', error);
                });

            }, 1300),
        }
    });
</script>

@endsection
