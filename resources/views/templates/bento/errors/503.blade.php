@extends('templates.bento.errors.layout')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Maintenance Mode'))
@section('description', __('We are currently performing scheduled maintenance. We will be back shortly.'))
