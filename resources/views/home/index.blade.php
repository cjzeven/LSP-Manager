@extends('layouts.app')

@section('content')
    
<div class="container" id="app">
    
</div>

<script type="text/babel">
    const vueObj = new Vue({
        el: '#app',
        data: {

        },
        methods: {
            greetings() {
                console.log('Hello universe');
            }
        },
        mounted() {
            console.log('Hello world');
        }
    });
</script>

@endsection