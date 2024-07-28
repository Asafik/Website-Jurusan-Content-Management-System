@extends('frontend.layouts.app')
@section('content')
    <!--page header section start-->
    <section class="page-header-section ptb-120 gradient-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center text-white">
                        <h2 class="text-white">Prestasi Mahasiswa Jurusan</h2>
                        <p class="lead">Prestasi Mahasiswa Jurusan Bisnis dan Informatika</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--page header section end-->

    <section class="ptb-80 gray-light-bg">
        <div class="container">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="section-heading mt-4">
                            <h3>Daftar Staf</h3>
                        </div>
                        <div class="row justify-content-between mb-4">
                            <div class="col-auto">
                                <div class="input-group">
                                    <span class="input-group-text">Show</span>
                                    <select id="entriesPerPage" class="form-control" onchange="changeEntriesPerPage()">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span class="input-group-text">entries</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <label for="searchInput" class="input-group-text">Cari</label>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Cari prestasi..." onkeyup="searchAchievements()">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> <!-- Tambahkan kelas responsive -->
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th></th>
                                        <th>Judul</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Tingkat</th>
                                        <th>Program Studi</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="achievementList">
                                    @foreach($achievements as $achievement)
                                        <tr class="achievement-item">
                                            <td></td>
                                            <td><img src="{{ asset('storage/images/achievement/' . $achievement->image) }}" class="img-fluid" alt="Achievement Image" style="max-width: 100px;"></td>
                                            <td>{{ $achievement->title }}</td>
                                            <td>{{ $achievement->achievementType->name }}</td>
                                            <td>{{ $achievement->achievementLevel->name }}</td>
                                            <td>{{ $achievement->achievementProgramStudi ->name }}</td>
                                            <td><a href="{{ url('presma-detail/' . $achievement->slug) }}" class="btn btn-primary">lihat</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center pagination-container">
                                <ul class="pagination modal-4">
                                    <li><a href="#" class="prev" onclick="previousPage()">
                                        <i class="fa fa-chevron-left"></i> Previous
                                    </a></li>
                                    <span id="paginationNumbers"></span>
                                    <li><a href="#" class="next" onclick="nextPage()">
                                        Next <i class="fa fa-chevron-right"></i>
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Pagination buttons end -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentPage = 1;
        let entriesPerPage = 10; // Jumlah entri per halaman diatur ke 10
        let searchResults = [];

        function searchAchievements() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const achievementItems = document.getElementsByClassName('achievement-item');
            searchResults = []; // Reset hasil pencarian

            for (let i = 0; i < achievementItems.length; i++) {
                const title = achievementItems[i].cells[2].textContent.toLowerCase();
                if (title.includes(input)) {
                    searchResults.push(achievementItems[i]); // Simpan item yang cocok
                }
            }
            loadPage(1); // Muat ulang halaman dengan hasil pencarian
        }

        function changeEntriesPerPage() {
            entriesPerPage = document.getElementById('entriesPerPage').value;
            loadPage(1);
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadPage(currentPage);
            }
        }

        function nextPage() {
            currentPage++;
            loadPage(currentPage);
        }

        function loadPage(page) {
            currentPage = page;
            entriesPerPage = parseInt(document.getElementById('entriesPerPage').value);
            const listItems = searchResults.length > 0 ? searchResults : document.querySelectorAll('#achievementList tr');
            const totalPages = Math.ceil(listItems.length / entriesPerPage);

            const startNumber = (currentPage - 1) * entriesPerPage;
            let endNumber = startNumber + entriesPerPage;

            document.querySelectorAll('#achievementList tr').forEach(item => {
                item.style.display = 'none';
            });

            for (let i = startNumber; i < endNumber && i < listItems.length; i++) {
                listItems[i].style.display = '';
                listItems[i].cells[0].textContent = i + 1;
            }

            const paginationNumbers = document.getElementById('paginationNumbers');
            paginationNumbers.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const pageNumberElement = document.createElement('li');
                const link = document.createElement('a');
                link.textContent = i;
                link.href = "#";
                link.onclick = function() { loadPage(i); };
                pageNumberElement.appendChild(link);
                if (i === currentPage) {
                    link.className = 'active';
                }
                paginationNumbers.appendChild(pageNumberElement);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadPage(1); // Memuat halaman pertama dengan 10 entri
        });
    </script>

    <style>
        .pagination-container {
            display: flex;
            justify-content: center; /* Memusatkan konten secara horizontal */
        }

        .pagination.modal-4 li {
            display: inline-block; /* Memastikan bahwa <li> ditampilkan secara inline-block */
        }

        .pagination.modal-4 a {
            margin: 0 5px;
            padding: 0;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 100%;
            background-color: #007bff; /* Warna biru primary */
            color: white;
            display: inline-block;
            text-align: center;
        }
        .pagination.modal-4 a:hover {
            background-color: #0056b3; /* Warna biru yang lebih gelap untuk hover */
        }
        .pagination.modal-4 a.active {
            background-color: #004080; /* Warna biru yang lebih gelap untuk aktif */
        }
        .pagination.modal-4 a.prev, .pagination.modal-4 a.next {
            width: 100px;
            border-radius: 50px;
        }
    </style>
@endsection
