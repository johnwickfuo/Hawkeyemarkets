@extends('templates.bento.errors.layout')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))
@section('description',
    $exception->getMessage() ?:
    __('The page has expired due to inactivity. Please refresh and try
    again.'))
