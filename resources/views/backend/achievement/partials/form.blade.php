<div class="modal fade text-left" id="achievementModal" tabindex="-1" role="dialog" aria-labelledby="achievementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="achievementModalLabel">Tambah Prestasi Mahasiswa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="achievementForm" action="{{ route('achievements.store') }}" method="POST" data-request="ajax" data-success-callback="{{ route('achievements') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group">
                        <label for="title">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Nama Prestasi" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="location">Lokasi Kejuaraan <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Lokasi Kejuaraan" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="description">Detail Kejuaraan <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" placeholder="Deskripsi" autocomplete="off"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="date">Tanggal Kejuaraan <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" placeholder="Tanggal Kejuaraan" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="achievement_type_id">Jenis Prestasi <span class="text-danger">*</span></label>
                        <select name="achievement_type_id" id="achievement_type_id" class="form-control">
                            <option value="">Pilih Jenis Prestasi</option>
                            @foreach ($achievementTypes as $item)
                                <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="achievement_level_id">Tingkat Prestasi <span class="text-danger">*</span></label>
                        <select name="achievement_level_id" id="achievement_level_id" class="form-control">
                            <option value="">Pilih Tingkat Prestasi</option>
                            @foreach ($achievementLevels as $item)
                                <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="achievement_program_studi_id">Program Studi <span class="text-danger">*</span></label>
                        <select name="achievement_program_studi_id" id="achievement_program_studi_id" class="form-control">
                            <option value="">Pilih Program Studi</option>
                            @foreach ($achievementProgramStudis as $item)
                                <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="file">Foto Penghargaan</label>
                        <input type="file" name="file" id="dropify" class="dropify">
                    </div>

                    <input type="hidden" name="slug" id="slug" value="">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="feather icon-send"></i> Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('_js')
    @parent
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script>
        $('#dropify').dropify();

        // Inisialisasi CKEditor untuk textarea konten
        var editor = CKEDITOR.replace('description');

        // Menyembunyikan notifikasi peringatan
        editor.on('instanceReady', function() {
            editor.on('notificationShow', function(event) {
                if (event.data.message.indexOf('This CKEditor 4.22.1 version is not secure.') !== -1) {
                    event.cancel();
                }
            });
        });

        // Fungsi untuk membuat slug
        function string_to_slug(str) {
            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // menghapus aksara dan mengganti spasi dengan -
            var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to   = "aaaaeeeeiiiioooouuuunc------";
            for (var i=0, l=from.length ; i<l ; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // menghapus karakter yang tidak valid
                .replace(/\s+/g, '-') // ganti spasi dengan -
                .replace(/-+/g, '-'); // gabungkan dash

            return str;
        }

        // Event ketika nilai pada input nama prestasi berubah
        $('#title').on('input', function() {
            var title = $(this).val();
            var slug = string_to_slug(title);
            $('#slug').val(slug); // Memperbarui nilai input tersembunyi
        });

    </script>
@endsection
