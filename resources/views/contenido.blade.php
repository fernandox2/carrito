@extends('layouts.app')

@section('content')

<template v-if="menu==0">
    <panel-component></panel-component>
</template>

<template v-if="menu==1">
<ventas-component></ventas-component>
</template>

<template v-if="menu==2">
    <productos-component></productos-component>
</template>

<template v-if="menu==3">
    <empresa-component></empresa-component>
</template>

<template v-if="menu==4">
    <reportes-component></reportes-component>
</template>

<template v-if="menu==5">
    <compras-component></compras-component>
</template>

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush