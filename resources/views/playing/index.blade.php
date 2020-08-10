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
    <div class="row">
        <div class="col-md-12">

            <div>
                <button class="btn btn-danger btn-sm" @click="handleCreatePlan">
                    Create Playing Plan
                </button>
            </div> <!-- Create plan button --> 

            <br>

            <table class="table">
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
                    <tr>
                        <td>1</td>
                        <td>Hello</td>
                        <td>300000</td>
                        <td>200000</td>
                        <td>150000</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Option buttons">
                                <button type="button" class="btn btn-sm btn-danger" @click="handleSpentModal">Spent</button>
                                <button type="button" class="btn btn-sm btn btn-outline-success" @click="handleDetailModal">Details</button>
                                <button type="button" class="btn btn-sm btn-outline-primary" @click="handleDeletePlan">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    @include('playing._createPlanModal')
    @include('playing._spentModal')
    @include('playing._planDetailsModal')

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
            handleSpentModal() {
                $('#spentModal').modal();
            },
            handleDetailModal() {
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