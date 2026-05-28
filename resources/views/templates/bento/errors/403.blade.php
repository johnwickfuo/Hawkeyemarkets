@extends('templates.bento.errors.layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Forbidden'))
@section('description', $exception->getMessage() ?: __('Access to this resource is denied. Please ensure you have the necessary permissions.'))
