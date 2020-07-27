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
                <td>IDR @{{ data.targetBudget }}</td>
                <td>IDR @{{ data.totalSpent }}</td>
                <td>IDR @{{ data.budgetLeft }}</td>
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
                requiredItems: [
                    {id: 1, name: 'Internet', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 2, name: 'Sampah', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 3, name: 'Sewa kontrakan', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 4, name: 'Galon air', amount: 0, paid: false, receiptPhoto: ''},
                ],
                targetBudget: 0,
            },
            payBillForm: {
                requiredItems: [
                    {id: 1, name: 'Internet', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 2, name: 'Sampah', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 3, name: 'Sewa kontrakan', amount: 0, paid: false, receiptPhoto: ''},
                    {id: 4, name: 'Galon air', amount: 0, paid: false, receiptPhoto: ''},
                ],
                regularItems: [
                    {id: (Math.random() + ''), name: 'Belanja minggu 1', amount: 0, paid: false, receiptPhoto: ''},
                    {id: (Math.random() + ''), name: 'Belanja minggu 2', amount: 0, paid: false, receiptPhoto: ''},
                    {id: (Math.random() + ''), name: 'Beli susu', amount: 0, paid: false, receiptPhoto: ''},
                    {id: (Math.random() + ''), name: 'Beli mecin', amount: 0, paid: false, receiptPhoto: ''},
                ],
                targetBudget: 0,
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
            detailsData: [],
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
            handlePayBill(id) {
                // TODO: mengambil detail dari plan berdasarkan id

                // TODO: isi modal dengan data detail

                // TODO: tampilkan modal
                $('#payBillModal').modal();
            },
            handlePayBillRemoveItem(id) {
                this.payBillForm.requiredItems = this.payBillForm.requiredItems.filter(item => item.id != id);
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
            handlePaid(id) {
                // TODO: send axios to update related item

                // TODO: cari data berdasarkan id tersebut dan disable form inputnya
                this.payBillForm.requiredItems = this.payBillForm.requiredItems.map(item => {
                    if (item.id == id) {
                        item.paid = !item.paid;
                    }
                    return item;
                });
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
            handlePayBillAddRequiredItem() {
                const name = prompt('Item Name');

                if (name.length > 0) {
                    const newItem = {
                        id: String(Math.random()),
                        name: name,
                        amount: 0,
                    }
                    this.payBillForm.requiredItems = [...this.payBillForm.requiredItems, newItem];
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