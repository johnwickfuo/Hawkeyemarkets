@extends('templates.bento.errors.layout')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Internal Server Error'))
@section('description', __('Something went wrong on our end. We are working to fix it as we speak.'))
