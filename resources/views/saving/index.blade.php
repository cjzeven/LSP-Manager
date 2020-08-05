@extends('layouts.app')

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
                <th>Date Time</th>
                <th>Target Budget</th>
                <th>Total Spent</th>
                <th>Budget Left</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>111</td>
                <td>111</td>
                <td>333</td>
                <td>444</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-danger" @click="handlePayBill()">Pay Bill</button>
                        <button type="button" class="btn btn-sm btn btn-outline-success" @click="handlePlanDetails()">Details</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="handleDeletePlan()">Delete</button>
                    </div>
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
            
        },
        methods: {
            handleCreatePlan() {
                $('#createPlanModal').modal();
            },
            doCreatePlan() {

            },
            handlePayBill() {
                $('#payBillModal').modal();
            },
            doHandlePayment() {
                
            },
            handlePlanDetails() {
                $('#planDetailsModal').modal();
            },
            handleDeletePlan() {

            }
        },
        mounted() {

        }
    });
</script>

@endsection