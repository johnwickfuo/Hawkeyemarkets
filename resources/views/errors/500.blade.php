@php
    $template = config('site.template', 'bento');
@endphp

@extends('templates.' . $template . '.errors.500')
