@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row mb-2">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="{{ route('borrowing_details.index') }}" class="btn btn-sm icon icon-left btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('borrowing_details.index') }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card kt-detail-card">
            <div class="card-header">
                Detail Data {{ $title }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-2">
                        <div class="row kt-detail-grid">
                            <div class='col-lg-2'><p>Borrowing Id</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->borrowing_id }}</p></div>
									<div class='col-lg-2'><p>Item Id</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->item_id }}</p></div>
									<div class='col-lg-2'><p>Kondisi Barang</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->kondisi_barang }}</p></div>
									<div class='col-lg-2'><p>Denda</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->denda }}</p></div>
									<div class='col-lg-2'><p>Jumlah</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->jumlah }}</p></div>
									<div class='col-lg-2'><p>Catatan</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $borrowing_details->catatan }}</p></div>
									
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@section('page-js')
@endsection

@section('inline-js')
@endsection
