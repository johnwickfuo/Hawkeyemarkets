@extends('templates.bento.blades.layouts.front')

@section('title', 'Light Streams')

@section('content')
    <section class="relative min-h-screen bg-[#050505] overflow-hidden flex items-center justify-center">
        @include('templates.bento.blades.partials.light-stream-effect')

        <div class="z-10 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Stream Effect</h1>
            <p class="text-text-secondary">Visualizing the flow.</p>
        </div>

    </section>

    {{-- countries animation --}}
    <section class="min-h-screen bg-[#020202] flex flex-col items-center justify-center relative">
        <h2 class="text-2xl text-white font-bold mb-8 z-10">Global Liquidity Network</h2>
        <div class="w-full max-w-4xl h-[600px]">
            @include('templates.bento.blades.partials.globe-wireframe')
        </div>
    </section>

@endsection
