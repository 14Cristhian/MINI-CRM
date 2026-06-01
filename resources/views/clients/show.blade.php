@extends('layouts.app')
@section('title', $client->name)

@section('content')
<div id="client-detail-app" data-client-id="{{ $client->id }}"></div>
@endsection
