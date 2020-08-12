@extends('layouts.app')

@section('title', 'Saving Plan')

@section('content')

<style>
    .fileupload-container {
        width: 380px !important;
    }
    .append-items {
        height: 37px;
    }
</style>

<div class="container" id="app">
    <div>
        <button class="btn btn-primary btn-sm" v-on:click="handleCreatePlan">
            Create Saving Plan
        </button>
    </div> <!-- Create plan button --> 

    <br>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Type</th>
                <th>Name</th>
                <th>Year</th>
                <th>Target</th>
                <th>Total Saving</th>
                <th>Target Left</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in savings">
                <td>@{{ item.id }}</td>
                <td>@{{ item.type == 1 ? 'Bank' : 'Reksadana' }}</td>
                <td>@{{ item.name }}</td>
                <td>@{{ item.years }}</td>
                <td>@{{ _format(item.target) }}</td>
                <td>@{{ _format(item.total) }}</td>
                <td>@{{ _format(item.targetLeft) }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-danger" @click="handlePayBill(item.id)">Pay Bill</button>
                        <button type="button" class="btn btn-sm btn btn-outline-success" @click="handlePlanDetails(item.id)">Details</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="handleDeletePlan(item.id)">Delete</button>
                    </div>
                </td>
            </tr>
            <tr v-if="savings.length <= 0">
                <td colspan="7">
                    <p class="text-center">No items yet.</p>
                </td>
            </tr>
        </tbody>
    </table>

    @include('saving._createPlanModal')
    @include('saving._payBillModal')
    @include('saving._planDetailsModal')

</div>

<script type="text/babel">
    new Vue({
        el: '#app',
        data: {
            createPlanForm: {
                name: '',
                years: 1,
                target: 0,
                type: 1,
            },
            paybillData: {
                form: {
                    saving_id: 0,
                    amount: 0,
                    datetime: '',
                    receipt_photo: '',
                },
                living: {},
                items: [],
            },
            savings: [],
            detailsData: {
                id: 0,
                name: '',
                type: '',
                totalSaving: 0,
                totalTarget: 0,
                totalLeft: 0,
            },
        },
        methods: {
            _format(value) {
                return moneyFormatIDR(value);
            },
            async getSavings() {
                try {
                    const response = await axios.get('{{ url("api/savings") }}');

                    if (response.status === 200) {
                        this.savings = response.data.map(saving => {
                            saving.total = saving.items.reduce((acc, item) => acc + item.amount, 0);
                            saving.targetLeft = saving.target - saving.total;
                            return saving;
                        })
                    }
                } catch (error) {
                    console.log('ERR getSavings', error);
                }
            },
            async getSavingItems(id) {
                try {
                    const response = await axios.get('{{ url("api/saving") }}/' + id);
                    
                    if (response.status === 200) {
                        this.paybillData.items = response.data.items;
                        this.paybillData.living = {
                            name: response.data.name,
                            id: response.data.id,
                            target: response.data.target, 
                        };
                    }

                } catch (error) {
                    console.log('ERR handlePayBill', error);
                }
            },
            handleCreatePlan() {
                $('#createPlanModal').modal();
            },
            async doCreatePlan() {
                try {
                    const response = await axios.post('{{ url("api/saving/create") }}', this.createPlanForm);

                    if (response.status === 200) {
                        this.getSavings();

                        $('#createPlanModal').modal('hide');
                    }
                } catch (error) {
                    console.log('ERR doCreatePlan', error);
                }
            },
            async handlePayBill(id) {
                this.paybillData.form.saving_id = id;
                await this.getSavingItems(id);
                $('#payBillModal').modal();
            },
            async doHandlePayment(id) {
                const file = document.getElementById('addPaymentUpload').files[0];

                let formData = new FormData();

                if (file) {
                    formData.append('file', file);
                }

                const {amount, datetime, saving_id} = this.paybillData.form;

                formData.append('amount', amount);
                formData.append('datetime', datetime);
                formData.append('saving_id', saving_id);

                try {
                    const response = await axios.post('{{ url("api/saving") }}/' + id + '/create', formData);

                    if (response.status === 200) {
                        this.getSavings();
                        this.getSavingItems(id);
                    }
                } catch (error) {
                    
                }
            },
            async handlePlanDetails(id) {
                try {
                    const response = await axios.get('{{ url("api/saving") }}/' + id);

                    if (response.status === 200) {
                        const {items, target, type, id, name} = response.data;
                        const totalSaving = items.reduce((acc, item) => acc + item.amount, 0);
                        
                        this.detailsData = {
                            id: id,
                            name: name,
                            type: type,
                            totalSaving: totalSaving,
                            totalTarget: target,
                            totalLeft: target - totalSaving,
                        };
                    }
                } catch (error) {
                    console.log('ERR handlePlanDetails');
                }
                $('#planDetailsModal').modal();
            },
            async handleDeletePlan(id) {

                if (!confirm('Are you sure to DELETE this plan?')) {
                    return false;
                }

                try {
                    const response = await axios.get('{{ url("api/saving") }}/' + id + '/delete');

                    if (response.status === 200) {
                        this.getSavings();
                    }

                } catch (error) {
                    console.log('ERR handleDeletePlan', error);
                }
            },
            async handleRemovePayment(id) {

                if (!confirm('Are you sure to DELETE this payment?')) {
                    return false;
                }

                try {
                    // remove payment history
                    const response = await axios.post('{{ url("api/saving/delete") }}/' + id);
                    
                    if (response.status === 200) {
                        this.getSavings();
                        this.getSavingItems(this.paybillData.form.saving_id);
                    }

                } catch (error) {
                    console.log('ERR handleRemovePayment', error);
                }
            },

        },
        mounted() {
            this.getSavings();

            $('#add-payment-date').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(),
            })
            .on('changeDate', e => {
                const date = moment(e.date).format('YYYY/MM/DD hh:mm:ss');
                this.paybillData.form.datetime = date;
            });
        },
        computed: {
            paybillTotalSpent() {
                return this.paybillData.items.reduce((acc, item) => acc + item.amount, 0);
            }
        }
    });
</script>

@endsection