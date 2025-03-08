@extends('adminlte::page')

@section('title', 'トップページ')

@section('content')
{{-- formを指定 --}}
<x-bs.card :form=true>

    <x-input.file caption="画像" id="image" />

    {{-- フッター --}}
    <x-slot name="footer">
        <div class="d-flex justify-content-end">
            {{-- 登録 --}}
            <x-button.submit-new />
        </div>
    </x-slot>

</x-bs.card>
@stop