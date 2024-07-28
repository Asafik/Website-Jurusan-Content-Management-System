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

                    <div class="col-12 text-right mb-2">
                        <button class="btn btn-primary waves-effect waves-light add" data-toggle="modal"
                            data-target="#achievementModal" type="button"><i class="feather icon-plus"></i> Tambah Prestasi</button>
                    </div>

                <table class="table zero-configuration" id="dataTable" data-url="{{ route('achievements.get-data') }}"
                    width="100%">
                    <thead>
                        <th>No.</th>
                        <th>Nama Prestasi</th>
                        <th>Lokasi Kejuaraan</th>
                        <th>jenis Prestasi</th>
                        <th>Tingkat Prestasi</th>
                        <th>Program Studi</th>
                        <th>Tanggal Kejuaraan</th>
                        <th>Publis</th>
                        <th></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('backend.achievement.partials.form')
@endsection
