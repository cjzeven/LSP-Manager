@extends('layouts.app')

@section('content')

<style>
    .modal-item {
        margin-left: -15px;
        margin-right: -15px;
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
                <td>IDR @{{ data.targetBudget }}</td>
                <td>IDR @{{ data.totalSpent }}</td>
                <td>IDR @{{ data.budgetLeft }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-danger" v-on:click="handlePayBill">Pay Bill</button>
                        <button type="button" class="btn btn-sm btn btn-outline-success">Details</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Delete</button>
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

</div>

<script type="text/babel">
    const vueObj = new Vue({
        el: '#app',
        data: {
            createPlanForm: {
                plan: 'new',
                dateTime: '',
                requiredItems: [
                    {id: 1, name: 'Internet', amount: 0},
                    {id: 2, name: 'Sampah', amount: 0},
                    {id: 3, name: 'Sewa kontrakan', amount: 0},
                    {id: 4, name: 'Galon air', amount: 0},
                ],
                targetBudget: 0,
            },
            payBillForm: {
                regularItems: [
                    {id: (Math.random() + ''), name: 'Belanja minggu 1', amount: 0},
                    {id: (Math.random() + ''), name: 'Belanja minggu 2', amount: 0},
                    {id: (Math.random() + ''), name: 'Beli susu', amount: 0},
                    {id: (Math.random() + ''), name: 'Beli mecin', amount: 0},
                ],
            },
            livingData: [
                {
                    id: 1,
                    datetime: '2020/07/24 09:18:01',
                    targetBudget: 3000000,
                    totalSpent: 2750000,
                    budgetLeft: 250000,
                },
                {
                    id: 2,
                    datetime: '2020/06/23 09:18:01',
                    targetBudget: 3000000,
                    totalSpent: 2750000,
                    budgetLeft: 250000,
                },
            ],
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
                    }
                    this.createPlanForm.requiredItems = [...this.createPlanForm.requiredItems, newItem];
                }
            },
            handleRemoveItem(id) {
                this.createPlanForm.requiredItems = this.createPlanForm.requiredItems.filter(item => item.id != id);
            },
            handleCreatePlanCreate() {
                axios.post('{{ url("api/living/createPlan") }}', {
                    requiredItems: [...this.createPlanForm.requiredItems],
                    targetBudget: this.createPlanForm.targetBudget,
                })
                .then(res => {
                    console.log(res);
                })
                .catch(err => {
                    alert(err);
                });
            },
            handlePayBill() {
                //request details axios

                // fill modal with data

                // show modal


                $('#payBillModal').modal();
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
                    }
                    this.payBillForm.regularItems = [...this.payBillForm.regularItems, newItem];
                }
            },
        },
        mounted() {
            $('#createPlanDateTime').datepicker({
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
                return this.createPlanForm.requiredItems.reduce((accumulator, item) => accumulator + parseFloat(item.amount), 0);
            }
        }
    });
</script>

@endsection