if (typeof table == 'undefined') {
    let table;
}

table = initTable('#dataTable', [{
        name: 'id',
        data: null,
        width: '1%',
        mRender: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    },
    {
        name: 'title',
        data: 'title',
    },
    {
        name: 'id',
        data: 'hashid',
        width: 150,
        sortable: false,
        mRender: function (data, type, row) {
            var render = ``;

            if (userPermissions.includes('update-iku-prodi-trpls')) {
                render += `<button class="btn btn-outline-primary btn-sm" type="button" onclick="editIkuProdiTrpl('${data}')"><i class="feather icon-edit"></i></button> `;
            }

            if (userPermissions.includes('delete-iku-prodi-trpls')) {
                render += `<button class="btn btn-outline-danger btn-sm" data-toggle="delete" data-id="${data}"><i class="feather icon-trash-2"></i></button> `;
            }

            return render;
        }
    }
]);

$('.add').on('click', function () {
    resetInvalid();
    $("#ikuProdiTrplForm")[0].reset();
    $('#ikuProdiTrplModal .modal-title').html('Tambah IKU Program Studi');
    $('#ikuProdiTrplModal form').attr('action', `${window.location.href}/store`);
});

function editIkuProdiTrpl(id) {
    $('#ikuProdiTrplModal form').attr('action', `${window.location.href}/${id}/update`);
    $("#ikuProdiTrplForm")[0].reset();

    fetch(`${window.location.href}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            resetInvalid();
            $('#ikuProdiTrplModal .modal-title').html('Edit IKU Program Studi');
            $('#title').val(data.data.title);
            $('#content').val(data.data.content);

            // Menghancurkan instance CKEditor yang ada jika ada
            if (CKEDITOR.instances['content']) {
                CKEDITOR.instances['content'].destroy(true);
            }

            // Menginisialisasi CKEditor dengan konten yang diambil
            var editor = CKEDITOR.replace('content', {
                // Opsi konfigurasi CKEditor di sini jika diperlukan
            });

            // Menyembunyikan notifikasi peringatan
            editor.on('notificationShow', function(event) {
                if (event.data.message.indexOf('This CKEditor 4.22.1 version is not secure.') !== -1) {
                    event.cancel();
                }
            });

            // Menampilkan modal
            $('#ikuProdiTrplModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}
