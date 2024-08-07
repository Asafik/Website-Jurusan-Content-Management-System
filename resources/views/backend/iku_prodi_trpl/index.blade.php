@extends('backend.layouts.ajax')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboards') }}" data-toggle="ajax">Home</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $title }}
        </li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                @can('create-iku-prodi-trpls')
                    <div class="col-12 text-right mb-2">
                        <button class="btn btn-primary waves-effect waves-light add" data-toggle="modal"
                            data-target="#ikuProdiTrplModal" type="button"><i class="feather icon-plus"></i> Tambah IKU Program Studi</button>
                    </div>
                @endcan
                <table class="table zero-configuration" id="dataTable" data-url="{{ route('iku-prodi-trpls.get-data') }}" width="100%">
                    <thead>
                        <th>No.</th>
                        <th>Tahun</th>
                        <th></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('backend.iku_prodi_trpl.partials.form')
@endsection
