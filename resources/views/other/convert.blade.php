@extends('layouts.template')

@section('content')
    <form method="post" action="{{ route('convertUpload') }}" enctype="multipart/form-data">
        @csrf
        <div class="">
            <input type="file" name="images[]"  multiple  accept="image/nef">
        </div>
        <button class="btn btn-default" type="submit">Отправить</button>
    </form>
@endsection