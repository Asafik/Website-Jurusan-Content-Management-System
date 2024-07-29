@extends('frontend.layouts.app')

@section('content')
    <!--hero section start-->
    <section class="ptb-120 gradient-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-8">
                    <div class="hero-content-wrap text-white text-center position-relative">
                        <h1 class="text-white">Indikator Kinerja Utama Jurusan</h1>
                        <p class="lead">Bisnis Dan Informatika Politeknik Negeri Banyuwangi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--hero section end-->

    <section class="page-header-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="container overflow-hidden text-center">
                    <div class="row gx-6">
                        <div class="col">
                            <div class="h-3 text-left">
                                <div class="post-content">
                                    <h4>Indikator Kinerja Utama</h4>
                                    <p>Indikator Kinerja Utama (IKU) adalah alat untuk mengukur dan mengevaluasi kinerja organisasi dalam mencapai tujuan strategis.</p>
                                    <p><strong>Berikut adalah data Indikator Kinerja Utama (IKU) Jurusan Bisnis Informatika:</strong></p>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Pilih Tahun
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @foreach ($ikus as $index => $iku)
                                                            <a class="dropdown-item {{ $index === 0 ? 'active' : '' }}" href="#" data-id="{{ $iku->id }}">{{ $iku->title }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="btn-group d-flex overflow-auto">
                                                    <a href="{{ url('/indikator-kinerja-utama-trpl') }}" class="btn btn-secondary text-nowrap">Prodi Teknologi Rekayasa Perangkat Lunak</a>
                                                    <a href="{{ url('/indikator-kinerja-utama-trk') }}" class="btn btn-secondary text-nowrap">Prodi Teknologi Rekayasa Komputer</a>
                                                    <a href="{{ url('/indikator-kinerja-utama-bisnis-digital') }}" class="btn btn-secondary text-nowrap">Prodi Bisnis Digital</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="iku-jurusan-content">
                                            <p id="iku-jurusan-content-text">Pilih Tahun diatas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('dropdownMenuButton');
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            const ikuJurusanContent = document.getElementById('iku-jurusan-content');

            // Function to load IKU Jurusan content based on ID
            function loadIkuJurusan(id, title) {
                // Update dropdown button text
                dropdownButton.textContent = title;

                fetch(`/load-iku-jurusan/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.content) {
                            ikuJurusanContent.innerHTML = data.content;
                        } else {
                            ikuJurusanContent.innerHTML = 'Halaman tidak ditemukan';
                        }
                    })
                    .catch(error => console.error('Error fetching IKU Jurusan:', error));
            }

            // Add click event listeners to dropdown items
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const title = this.textContent.trim();
                    loadIkuJurusan(id, title);
                });
            });

            // Load content for the first dropdown item by default
            const firstItem = dropdownItems[0];
            if (firstItem) {
                const id = firstItem.dataset.id;
                const title = firstItem.textContent.trim();
                loadIkuJurusan(id, title);
            }
        });
    </script>

    <style>
        .btn-group {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
        }

        .btn-group .btn {
            margin-right: 10px; /* Atur jarak antar tombol jika diperlukan */
        }

        .card {
            overflow: hidden;
        }

        .card-body {
            max-height: 600px; /* Atur tinggi maksimum area konten sesuai kebutuhan */
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd; /* Garis batas untuk tabel */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd; /* Garis batas untuk sel */
        }

        .text-nowrap {
            white-space: nowrap;
        }
    </style>
@endsection
