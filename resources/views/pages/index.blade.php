@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    {{-- formを指定 --}}
<x-bs.card :form=true>

    <x-input.file caption="画像パス" id="image_path" />

    {{-- フッター --}}
    <x-slot name="footer">
        <div class="d-flex justify-content-end">
            {{-- 登録時 --}}
            <button type="button" class="btn btn-success">
                <i class="fas fa-paper-plane"></i>
                送信
            </button>
            <x-button.submit-new />
        </div>
    </x-slot>

</x-bs.card>
@stop