@extends('layouts.app')

@section('title', 'Playing Plan')

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
    <div class="row">
        <div class="col-md-12">

            <div>
                <button class="btn btn-danger btn-sm" @click="handleCreatePlan">
                    Create Playing Plan
                </button>
            </div> <!-- Create plan button --> 

            <br>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Budget</th>
                            <th>Total Spent</th>
                            <th>Budget Left</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in playingData">
                            <td>@{{ index + 1 }}</td>
                            <td>@{{ item.name }}</td>
                            <td>@{{ _format(item.target_budget) }}</td>
                            <td>@{{ _format(calculateTotalSpent(item.items)) }}</td>
                            <td>@{{ _format(item.target_budget - calculateTotalSpent(item.items)) }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Option buttons">
                                    <button type="button" class="btn btn-sm btn-danger" @click="handleSpentModal(item.id)">Spent</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" @click="handleDeletePlan(item.id)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!playingData.length">
                            <td colspan="6">
                                <p class="text-center">No items yet.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('playing._createPlanModal')
    @include('playing._spentModal')

</div>

<script type="text/babel">
    new Vue({
        el: '#app',
        data: {
            createPlanForm: {
                name: '',
                target_budget: '',
                datetime: '',
            },
            spentForm: {
                payment: {
                    playing_id: 0,
                    datetime: '',
                    amount: '',
                    receipt_photo: '',
                },
                playingItems: [],
                name: '',
            },
            playingData: [],
        },
        methods: {
            _formatDate(date) {
                return moment(new Date(date)).format('DD MMMM YYYY');
            },
            async getPlayingData() {
                try {
                    const response = await axios.get('{{ url("api/playings") }}');
                    
                    if (response.status === 200) {
                        this.playingData = response.data;
                    }
                } catch (error) {
                    console.log('ERR getPlayingData', error);
                }
            },
            async getPlayingItemData(id) {
                try {
                    const response = await axios.get('{{ url("api/playing") }}/' + id);

                    if (response.status === 200) {
                        this.spentForm.playingItems = response.data.items;
                        this.spentForm.name = response.data.name;
                    }
                } catch (error) {
                    console.log('ERR getPlayingItemData');
                }
            },
            handleCreatePlan() {
                $('#createPlanModal').modal();
            },
            async doHandleCreatePlan() {
                try {
                    let response = await axios.post('{{ url("api/playing/create") }}', this.createPlanForm);

                    if (response.status === 200) {
                        this.getPlayingData();

                        $('#createPlanModal').modal('hide');
                    }
                } catch (error) {
                    console.log('ERR doHandleCreatePlan');
                }
            },
            async handleSpentModal(id) {
                this.spentForm.payment.playing_id = id;

                await this.getPlayingItemData(id);

                $('#spentModal').modal();
            },
            async handleDetailModal(id) {
                $('#planDetailsModal').modal();
            },
            async handleDeletePlan(id) {

                if (!confirm('Are you sure to DELETE?')) {
                    return;
                }

                try {
                    const response = await axios.get('{{ url("api/playing") }}/' + id + '/delete');

                    if (response.status === 200) {
                        this.getPlayingData(id);
                    }
                } catch (error) {
                    console.log('ERR handleDeletePlan', error);
                }
            },
            async handleSpentDelete(id) {

                if (!confirm('Are you sure to DELETE?')) {
                    return;
                }

                try {
                    const response = await axios.get('{{ url("api/playing/delete") }}/' + id);

                    if (response.status === 200) {
                        await this.getPlayingItemData(this.spentForm.payment.playing_id);
                        this.getPlayingData();
                    }
                } catch (error) {
                    console.log('ERR handleSpentDelete', error);
                }
            },
            async handleSpentPay() {
                const file = document.getElementById('addPaymentUpload').files[0];

                let formData = new FormData();

                if (file) {
                    formData.append('file', file);
                }

                const {amount, datetime, playing_id} = this.spentForm.payment;

                formData.append('amount', amount);
                formData.append('datetime', datetime);
                formData.append('playing_id', playing_id);

                try {
                    const response = await axios.post('{{ url("api/playing") }}/' + playing_id + '/create', formData);

                    if (response.status === 200) {
                        await this.getPlayingItemData(playing_id);
                        this.getPlayingData();
                    }
                } catch (error) {
                    console.log('ERR handleSpentPay', error);
                }
            },
            calculateTotalSpent(item) {
                return item.reduce((acc, item) => acc + item.amount, 0);
            },
            _format(value) {
                return moneyFormatIDR(value);
            },
        },
        mounted() {
            this.getPlayingData();

            $('#createPlanDatepicker').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(),
            })
            .on('changeDate', e => {
                const date = moment(e.date).format('YYYY/MM/DD hh:mm:ss');
                this.createPlanForm.datetime = date;
            });

            $('#createSpentDatepicker').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(),
            })
            .on('changeDate', e => {
                const date = moment(e.date).format('YYYY/MM/DD hh:mm:ss');
                this.spentForm.payment.datetime = date;
            });
        },
        computed: {
            targetBudget() {
                const id = this.spentForm.payment.playing_id;
                const data = this.playingData.find(item => item.id === id);
                return data ? data.target_budget : 0;
            },
            totalSpent() {
                return this.spentForm.playingItems.reduce((acc, item) => acc + item.amount, 0);
            },
            budgetLeft() {
                return this.targetBudget - this.totalSpent;
            }
        }
    });
</script>

@endsection