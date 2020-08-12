@extends('layouts.app')

@section('content')

<style>
    * {
        transition: all .5s;
    }
</style>
    
<br>

<div class="container" id="app">
    <div class="row">
        <div class="col-sm-12 col-lg">
            <div class="card mb-3 w-100">
                <div class="card-header bg-primary text-white">Living Plan</div>
                <div class="card-body">
                    <ul class="p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            Overall Spent
                            <span class="badge badge-secondary badge-pill">@{{ _format(livingPlan.overallTotal) }}</span>
                        </li>
                        <li class="d-none justify-content-between align-items-center">
                            Latest Plan
                            <span class="badge badge-secondary badge-pill">@{{ _formatDate(livingPlan.latestPlan) }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            Total Plan
                            <span class="badge badge-secondary badge-pill">@{{ livingPlan.totalPlan }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg">
            <div class="card mb-3 w-100">
                <div class="card-header bg-danger text-white">Saving Plan</div>
                <div class="card-body">
                    <ul class="p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            Overall Saving
                            <span class="badge badge-secondary badge-pill">@{{ _format(savingPlan.overallTotal) }}</span>
                        </li>
                        <li class="d-none justify-content-between align-items-center">
                            Latest Plan
                            <span class="badge badge-secondary badge-pill">@{{ _formatDate(savingPlan.latestPlan) }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            Total Plan
                            <span class="badge badge-secondary badge-pill">@{{ savingPlan.totalPlan }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg">
            <div class="card mb-3 w-100">
                <div class="card-header bg-success text-white">Playing Plan</div>
                <div class="card-body">
                    <ul class="p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            Overall Spent
                            <span class="badge badge-secondary badge-pill">@{{ _format(playingPlan.overallTotal) }}</span>
                        </li>
                        <li class="d-none justify-content-between align-items-center">
                            Latest Plan
                            <span class="badge badge-secondary badge-pill">@{{ _formatDate(playingPlan.latestPlan) }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            Total Plan
                            <span class="badge badge-secondary badge-pill">@{{ playingPlan.totalPlan }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
    const vueObj = new Vue({
        el: '#app',
        data: {
            livingPlan: {
                overallTotal: 0,
                latestPlan: Date.now(),
                totalPlan: 0,
            },
            savingPlan: {
                overallTotal: 0,
                latestPlan: Date.now(),
                totalPlan: 0,
            },
            playingPlan: {
                overallTotal: 0,
                latestPlan: Date.now(),
                totalPlan: 0,
            },
        },
        methods: {
            _formatDate(date) {
                return moment(new Date(date)).format('DD MMMM YYYY');
            },
            _format(value) {
                return moneyFormatIDR(value);
            },
            async getLivingData() {
                try {
                    const response = (await axios.get('api/living/all'));

                    if (response.status === 200) {
                        const totalData = response.data.length;
                        const overallTotal = response.data.reduce((acc, item) => acc + item.total_spent, 0);
                        const latestPlan = response.data[totalData-1].datetime;
                        const totalPlan = totalData;
                        
                        this.livingPlan = { overallTotal, latestPlan, totalPlan };
                    }
                } catch (error) {
                    console.log('ERR getLivingData', error);
                }
            },
            async getSavings() {
                try {
                    const response = await axios.get('{{ url("api/savings") }}');

                    if (response.status === 200) {
                        const totalData = response.data.length;

                        const overallTotal = response.data.map(item => {
                            return item.items.reduce((acc, i) => acc + i.amount, 0);
                        }).reduce((acc, item) => acc + item, 0);
                        
                        const latestPlan = Date.now();

                        const totalPlan = totalData;
                        
                        this.savingPlan = { overallTotal, latestPlan, totalPlan };
                    }
                } catch (error) {
                    console.log('ERR getSavings', error);
                }
            },
            async getPlayingData() {
                try {
                    const response = await axios.get('{{ url("api/playings") }}');
                    
                    if (response.status === 200) {
                        const totalData = response.data.length;

                        const overallTotal = response.data.map(item => {
                            return item.items.reduce((acc, i) => acc + i.amount, 0);
                        }).reduce((acc, item) => acc + item, 0);
                        
                        const latestPlan = response.data[totalData-1].datetime;

                        const totalPlan = totalData;
                        
                        this.playingPlan = { overallTotal, latestPlan, totalPlan };
                    }
                } catch (error) {
                    console.log('ERR getPlayingData', error);
                }
            },
        },
        mounted() {
            this.getLivingData();
            this.getSavings();
            this.getPlayingData();
        }
    });
</script>

@endsection