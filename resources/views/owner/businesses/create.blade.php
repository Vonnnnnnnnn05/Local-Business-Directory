@extends('layouts.app')

@section('content')
    <div class="panel stack">
        <h1>Add Business</h1>
        <form class="stack" method="post" action="{{ route('owner.businesses.store') }}" enctype="multipart/form-data">
            @include('owner.businesses._form')
        </form>
    </div>
@endsection
