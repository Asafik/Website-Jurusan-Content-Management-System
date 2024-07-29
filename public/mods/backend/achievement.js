if (typeof table == 'undefined') {
    let table
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
        name: "title",
        data: "title",
    },
    {
        name: "location",
        data: "location",
        mRender: function (data) {
            return data == null ? '-' : data;
        }
    },
    {
        name: 'achievement_type',
        data: 'achievement_type',
    },
    {
        name: 'achievement_level',
        data: 'achievement_level',
    },
    {
        name: 'achievement_program_studi',
        data: 'achievement_program_studi',
    },
    {
        name: 'date',
        data: 'date',
        mRender: function (data) {
            if (data == null) {
                return '-';
            } else {
                // Konversi tanggal menjadi objek JavaScript Date
                var dateObj = new Date(data);
                // Ambil bulan dari tanggal (diindeks dari 0 hingga 11, jadi tambahkan 1)
                var month = dateObj.toLocaleString('default', { month: 'long' });
                // Ambil tanggal dan tambahkan nol di depan jika diperlukan
                var day = String(dateObj.getDate()).padStart(2, '0');
                // Format tanggal dengan bulan yang ditambahkan
                return day + ' ' + month + ' ' + dateObj.getFullYear();
            }
        }
    },

    {
        name: 'is_publish',
        data: 'is_publish',
        mRender: function (data, type, row) {
            return `
                <div class="custom-control custom-switch switch-lg custom-switch-primary">
                    <input type="checkbox" class="custom-control-input" id="${row.hashid}" ${data == true ? 'checked' : ''} onchange="updateStatus('${row.hashid}')">
                    <label class="custom-control-label" for="${row.hashid}">
                        <span class="switch-text-left">Aktif</span>
                        <span class="switch-text-right">Tidak Aktif</span>
                    </label>
                </div>
            `
        }
    },

    {
        name: 'id',
        data: 'hashid',
        width: 150,
        sortable: false,
        mRender: function (data, type, row) {
            var render = ``
                render += `<button class="btn btn-outline-primary btn-sm" type="button" onclick="editAchievement('${data}')"><i class="feather icon-edit"></i></button> `
                render += `<button class="btn btn-outline-danger btn-sm" data-toggle="delete" data-id="${data}"><i class="feather icon-trash-2"></i></button> `


            return render
        }
    }
]);



$('.add').on('click', function () {
    resetInvalid();
    $("#achievementForm")[0].reset()
    $('#achievementModal .modal-title').html('Tambah Prestasi');
    $('#achievementModal form').attr('action', `${window.location.href}/store`);
});



function editAchievement(id) {
    $('#achievementModal form').attr('action', `${window.location.href}/${id}/update`);
    $("#achievementForm")[0].reset();
    fetch(`${window.location.href}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            resetInvalid();
            $('#achievementModal .modal-title').html('Edit Prestasi');
            $('#title').val(data.data.title);
            $('#location').val(data.data.location);
            $('#achievement_type_id').val(data.achievement_type_id);
            $('#achievement_level_id').val(data.achievement_level_id);
            $('#date').val(data.data.date);
            $('#description').val(data.data.description);
            $('#achievement_program_studi_id').val(data.achievement_program_studi_id);
            $('#slug').val(data.data.slug);


            // Destroy existing CKEditor instance if exists
            if (CKEDITOR.instances['description']) {
                CKEDITOR.instances['description'].destroy(true);
            }

            // Initialize CKEditor for the 'description' textarea
            CKEDITOR.replace('description', {
                // CKEditor configuration options here, if needed
            });

            // Hide notification warning
            CKEDITOR.instances['description'].on('notificationShow', function(event) {
                if (event.data.message.indexOf('This CKEditor 4.22.1 version is not secure.') !== -1) {
                    event.cancel();
                }
            });

            $('#achievementModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}



async function updateStatus(hashid) {
    swal.fire({
        title: 'Porcessing',
        html: 'Sedang memperbarui data',
        allowOutsideClick: false,
        didOpen: () => {
            swal.showLoading()
        }
    })

    const res = await fetch(`${window.location.href}/${hashid}/update-status`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })

    swal.close()
    if (res.status == 200) {
        var data = await res.json()

        notify('success', data.message)

        if (typeof table != 'undefined') table.ajax.reload()
        else handleView()
    } else {
        if (res.status == 401) {
            window.location.assign()
        } else {
            var data = await res.json()
            notify('warning', data.message)
        }
    }
}
