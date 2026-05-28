@extends('templates.bento.errors.layout')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message', __('Payment Required'))
@section('description', $exception->getMessage() ?: __('Payment is required to proceed. Please check your billing
    information.'))
