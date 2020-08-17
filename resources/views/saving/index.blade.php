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

    <div class="table-responsive">
        <b-table
            :id="tableName"
            :items="getSavings"
            :per-page="perPage"
            :current-page="currentPage"
            :fields="fields"
            :striped="true"
            :hover="true"
            :busy.sync="isBusy"
        >
            <template v-slot:cell(id)="row">
                @{{ parseInt(from) + parseInt(row.index) }}
            </template>

            <template v-slot:cell(type)="row">
                @{{ row.item.type == 1 ? 'Bank' : 'Reksadana' }}
            </template>

            <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle" variant="secondary" small></b-spinner>
                    <strong class="text-secondary" small>Loading...</strong>
                </div>
            </template>

            <template v-slot:cell(options)="row">
                <b-button-group size="sm">
                    <b-button @click="handlePayBill(row.item.id)" variant="danger">
                        Pay Bill
                    </b-button>
                    <b-button @click="handlePlanDetails(row.item.id)" variant="outline-success">
                        Details
                    </b-button>
                    <b-button @click="handleDeletePlan(row.item.id)" variant="outline-info">
                        Delete
                    </b-button>
                </b-button-group>
            </template>
        </b-table>
    </div>

    
    <div class="d-flex justify-content-between align-items-center">
        <b-pagination
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            :aria-controls="tableName"
            size="sm"
        >
        </b-pagination>
        <p class="mt-3">Page: @{{ currentPage }} of @{{ totalPages }}</p>
    </div>

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
            detailsData: {
                id: 0,
                name: '',
                type: '',
                totalSaving: 0,
                totalTarget: 0,
                totalLeft: 0,
            },
            // Pagination
            tableName: 'my-table',
            perPage: 0,
            currentPage: 1,
            items: [],
            fields: [
                { key: 'id', label: 'No' },
                { key: 'type', label: 'Type' },
                { key: 'name', label: 'Name' },
                { key: 'year', label: 'Year' },
                { key: 'target_budget', label: 'Target' },
                { key: 'total_saving', label: 'Total Saving' },
                { key: 'target_left', label: 'Target Left' },
                { key: 'options', label: 'Options' },
            ],
            rows: 0,
            totalPages: 0,
            isBusy: false,
            from: 0,
            // End pagination
        },
        methods: {
            _format(value) {
                return moneyFormatIDR(value);
            },
            _formatDate(date) {
                return moment(new Date(date)).format('DD MMMM YYYY');
            },
            async getSavings(ctx, callback) {
                this.isBusy = true;

                const params = '?page=' + ctx.currentPage;

                try {
                    const response = await axios.get('{{ url("api/savings") }}' + params);

                    if (response.status === 200) {

                        this.rows = response.data.total;
                        this.perPage = response.data.per_page;
                        this.currentPage = response.data.current_page;
                        this.totalPages = response.data.last_page;
                        this.from = response.data.from;

                        return response.data.data.map(saving => {
                            const totalSaving = saving.items.reduce((acc, item) => acc + item.amount, 0);
                            return {
                                id: saving.id,
                                name: saving.name,
                                type: saving.type,
                                year: saving.years,
                                target_budget: this._format(saving.target),
                                total_saving: this._format(totalSaving),
                                target_left: this._format(saving.target - totalSaving),
                            };
                        });

                        this.isBusy = false;
                    }
                } catch (error) {
                    console.log('ERR getSavings', error);
                    this.isBusy = false;
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
                        this.$root.$emit('bv::refresh::table', this.tableName);

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
                        this.getSavingItems(id);
                        this.$root.$emit('bv::refresh::table', this.tableName);
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
                        this.$root.$emit('bv::refresh::table', this.tableName);
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
                        this.getSavingItems(this.paybillData.form.saving_id);
                        this.$root.$emit('bv::refresh::table', this.tableName);
                    }

                } catch (error) {
                    console.log('ERR handleRemovePayment', error);
                }
            },

        },
        mounted() {

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
            },
        }
    });
</script>

@endsection