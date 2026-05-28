@extends('templates.bento.errors.layout')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('description',
    $exception->getMessage() ?:
    __('You are making too many requests. Please wait a moment before
    trying again.'))
