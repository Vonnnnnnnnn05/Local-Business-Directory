@extends('layouts.app')

@section('content')
    <div class="panel stack">
        <h1>Edit Business</h1>
        <form class="stack" method="post" action="{{ route('owner.businesses.update', $business) }}" enctype="multipart/form-data">
            @include('owner.businesses._form')
        </form>
    </div>
@endsection
