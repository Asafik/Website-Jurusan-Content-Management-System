<div class="modal fade text-left" id="ikuProdiBisnisDigitalModal" tabindex="-1" role="dialog" aria-labelledby="ikuProdiBisnisDigitalModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ikuProdiBisnisDigitalModal">Tambah IKU Program Studi Bisnis Digital</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ikuProdiBisnisDigitalForm" action="{{ route('iku-prodi-bisnis-digitals.store') }}" method="POST" data-request="ajax" data-success-callback="{{ route('iku-prodi-bisnis-digitals') }}">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group">
                        <label for="title">Tahun Indikator Kinerja Utama <span class="text-danger">*</span></label>
                        <input type="number" name="title" id="title" class="form-control" placeholder="Tahun Indikator Kinerja Utama" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="content">Tabel Indikator Kinerja Utama <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" placeholder="Tabel Indikator Kinerja Utama" rows="5" autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="feather icon-send"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
<script>
    // Inisialisasi CKEditor untuk textarea konten
    var editor = CKEDITOR.replace('content');

    // Menyembunyikan notifikasi peringatan
    editor.on('instanceReady', function() {
        editor.on('notificationShow', function(event) {
            if (event.data.message.indexOf('This CKEditor 4.22.1 version is not secure.') !== -1) {
                event.cancel();
            }
        });
    });

    // Update data CKEditor saat form disubmit
    document.querySelector('#ikuProdiBisnisDigitalForm').addEventListener('submit', function() {
        // Update semua instance CKEditor yang ada
        for (const instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>
