@extends('layouts.app')

@section('content')
<div class = "pt-96 text-center">
    <h1>Bem-vindo ao SportsHub</h1>
    <p>Encontre e reserve os melhores espaços desportivos.</p>

    {{-- Adicione aqui o conteúdo da homepage --}}
    <div class="featured-spaces pt-8">
        <h2>Enquanto que o website não se encontra pronto, fique sentado a apreciar esta praia ⮧</h2>
        <img src="../../../public/images/praia.jpg" alt="🏖️">
        {{-- Lista de espaços será adicionada aqui --}}
    </div>
</div>
@endsection
