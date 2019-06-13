@extends('layouts.template')

@section('content')
    <div class="row client-editable"
         data-action="{{ route('CrmPageUpdate', $client->id) }}"
         data-access-action="{{ route('AccessUpdate', $client->id) }}">
        @include('crm.chunk.description')
        @include('crm.chunk.access')
    </div>
    <div class="row">
        @include('crm.chunk.chat')
        @include('crm.chunk.tasks')
    </div>
    <div class="row">
        @include('crm.chunk.files')
        @include('crm.chunk.payments')
    </div>


@endsection