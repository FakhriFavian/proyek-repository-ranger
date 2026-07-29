@extends('layouts.app')

@section('main')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <p class="kt-eyebrow mb-1">Entry Management</p>
                <h3 class="mb-0">Form Tambah {{ $title }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('userrole.index') }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Form Tambah {{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card kt-form-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fa fa-info-circle text-primary"></i>
                <span>Form Tambah Data {{ $title }}</span>
            </div>
            <div class="card-body">
                @include('include.flash')
                <form class="form form-horizontal" action="{{ route('userrole.store') }}" method="POST">
                    <div class="form-body">
                        @csrf
                        @foreach ($forms as $key => $field)
                            <div class="row mb-3">
                                <div class="col-md-3 text-sm-start text-md-end pt-2">
                                    <label>{{ $field['label'] }}</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <x-dynamic-field :name="$key" :field="$field" />
                                    @error($key)
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                        <div class="offset-md-3 ps-2 pt-2 d-flex gap-2">
                            <button class="btn btn-primary icon icon-left" type="submit"><i class="fa fa-arrow-right"></i> Simpan</button>
                            <a href="{{ route('userrole.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                  </div>
                </form>
            </div>
        </div>

    </section>
</div>
@endsection
