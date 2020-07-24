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
                <th>Month - Year</th>
                <th>Target Budget</th>
                <th>Total Spent</th>
                <th>Budget Left</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>June - 2020</td>
                <td>IDR 3.000.000</td>
                <td>IDR 2.750.000</td>
                <td>IDR 250.000</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-danger">Pay Bill</button>
                        <button type="button" class="btn btn-sm btn btn-outline-success">Details</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Delete</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="createPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!-- Vertically centered scrollable modal -->
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <label>
                                <input type="radio" value="new" name="plan" v-model="plan">
                                &nbsp;New
                            </label>
                            <label style="margin-left: 10px;">
                                <input type="radio" value="existing" name="plan" v-model="plan">
                                &nbsp;Existing
                            </label>
                        </div>
                        <br>
                        <div class="modal-item" v-if="plan == 'new'">
                            <div>
                                <h5>Target Budget</h5>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="IDR" v-model="targetBudget">
                                </div>
                            </div>
                            
                            <div>
                                <h5>Required Items</h5>
                                <div class="form-group" v-for="item in requiredItems">
                                    <label>@{{ item.name }} &nbsp;<a href="javascript:;" v-on:click="handleRemoveItem(item.id)">x</a></label>
                                    <input type="text" class="form-control" placeholder="IDR" v-model="item.amount">
                                </div>
                                <button class="btn btn-danger" v-on:click="handleAddRequiredItem">Add Item</button>
                            </div>
                        </div>

                        <div class="modal-item" v-if="plan == 'existing'">
                            <div>
                                <h5>Select Month</h5>
                                <select name="plan" class="form-control">
                                    <option value="1">June - 2020</option>
                                    <option value="2">Mei - 2020</option>
                                    <option value="2">April - 2020</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <p>Total Items: <strong>IDR @{{ calculateRequiredItemTotal }}</strong></p>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="handleCreatePlanCreate">Create</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/babel">
    const vueObj = new Vue({
        el: '#app',
        data: {
            plan: 'new',
            requiredItems: [
                {id: 1, name: 'Internet', amount: 0},
                {id: 2, name: 'Sampah', amount: 0},
                {id: 3, name: 'Sewa kontrakan', amount: 0},
                {id: 4, name: 'Galon air', amount: 0},
            ],
            targetBudget: 0,
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
                    this.requiredItems = [...this.requiredItems, newItem];
                }
            },
            handleRemoveItem(id) {
                this.requiredItems = this.requiredItems.filter(item => item.id != id);
            },
            handleCreatePlanCreate() {
                axios.post('{{ url("api/living/createPlan") }}', {
                    requiredItems: [...this.requiredItems],
                    targetBudget: this.targetBudget,
                })
                .then(res => {
                    console.log(res);
                })
                .catch(err => {
                    alert(err);
                });
            }
        },
        mounted() {

        },
        updated() {

        },
        computed: {
            calculateRequiredItemTotal() {
                return this.requiredItems.reduce((accumulator, item) => accumulator + parseFloat(item.amount), 0);
            }
        }
    });
</script>

@endsection