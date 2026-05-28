@extends('templates.bento.errors.layout')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Authorization Required'))
@section('description', $exception->getMessage() ?: __('Access to this resource is denied. Please ensure you have the necessary permissions.'))
