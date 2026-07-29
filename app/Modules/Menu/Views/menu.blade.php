@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-end mb-2">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <p class="kt-eyebrow mb-1">Data Management</p>
                <h3 class="mb-0">{{ $title }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card kt-table-card">
            <div class="card-body">
                <div class="row align-items-center mb-3 g-2">
                    <div class="col-12 col-md-9">
                        <form action="{{ route('menu.index') }}" method="get">
                            <div class="form-group has-icon-left position-relative kt-search-input">
                                <input type="text" class="form-control rounded-pill" value="{{ request()->get('search') }}" name="search" placeholder="Search">
                                <div class="form-control-icon"><i class="fa fa-search"></i></div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-3 text-md-end">
                        {!! button('menu.create', 'Menu') !!}
                    </div>
                </div>
                @include('include.flash')
                <div class="table-responsive-md col-12">
                    <table class="table table-hover align-middle kt-table" id="table1">
                        <thead>
                            <tr>
                                <th width="15">No</th>
								<td>Menu</td>
                                <td>Icon</td>
								<td>Tampilkan?</td>
								<td>Level</td>
								<td>Module</td>
								<td>Induk</td>
								<td>Routing</td>
								<td>Urutan</td>

                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = $data->firstItem(); @endphp
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
									<td>{{ $item->menu }}</td>
                                    <td>{!! '<i class="fa '.$item->icon.'"></i>' !!}</td>
									<td>{{ $item->is_tampil ? 'Ya' : 'Tidak' }}</td>
									<td>{{ $item->level == 0 ? 'Group Menu' : ($item->level == 1 ? 'Menu' : 'Submenu') }}</td>
									<td>{{ $item->module }}</td>
									<td>{{ @$item->menuref->menu }}</td>
									<td>{{ $item->routing }}</td>
									<td>{{ $item->urutan }}</td>

                                    <td>
                                        {!! button('menu.edit', 'Menu', $item->id) !!}
                                        {!! button('menu.destroy', 'Menu', $item->id) !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center"><i>No data.</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
				{{ $data->links() }}
            </div>
        </div>

    </section>
</div>
@endsection

@section('page-js')
@endsection

@section('inline-js')
@endsection
