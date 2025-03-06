function fetchShowBy({
    url,                // Url request
    model,              // Nama model untuk request
    key,                // Kolom yang digunakan sebagai filter
    data,               // Nilai yang dikirimkan dalam request
    isCollection,       // Apakah hasil berupa koleksi atau objek tunggal (1 atau 0)
    affectSelectorId,   // Selector yang akan diisi dengan option baru
    optionPlaceholder   // Placeholder untuk option pertama
}) {
    if (!data) {
        $(affectSelectorId).empty().append(`<option value="">${optionPlaceholder}</option>`).trigger('change');
        return;
    }

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            model: model,
            key: key,
            data: data,
            is_collection: isCollection ? 1 : 0
        },
        success: function (response) {
            if (response.success) {
                let selectElement = $(affectSelectorId);
                selectElement.empty();
                selectElement.append(`<option value="">${optionPlaceholder}</option>`);

                if (Array.isArray(response.data)) {
                    $.each(response.data, function (index, item) {
                        selectElement.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                } else if (typeof response.data === 'object' && response.data !== null) {
                    selectElement.append(`<option value="${response.data.id}">${response.data.name}</option>`);
                }

                selectElement.trigger('change'); // Refresh Select2 jika digunakan
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function fetchByRelation({
    url,                // URL request
    model,              // Nama model untuk request
    relation,           // Nama relasi untuk request (contoh: 'client.addresses')
    key,                // Kolom yang digunakan sebagai filter
    data,               // Nilai yang dikirimkan dalam request
    isCollection,       // Apakah hasil berupa koleksi atau objek tunggal (1 atau 0)
    affectSelectorId,   // Selector yang akan diisi dengan option baru
    optionPlaceholder   // Placeholder untuk option pertama
}) {
    if (!data) {
        $(affectSelectorId).empty().append(`<option value="">${optionPlaceholder}</option>`).trigger('change');
        return;
    }

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            model: model,
            key: key,
            relation: relation,
            data: data,
            is_collection: isCollection ? 1 : 0
        },
        success: function (response) {
            if (response.success) {
                console.log('Response fetchRelation',response);
                let selectElement = $(affectSelectorId);
                selectElement.empty();
                selectElement.append(`<option value="">${optionPlaceholder}</option>`);

                // Cek apakah relasi direspons
                let relationData = getNestedProperty(response.data, relation);

                if (!relationData) {
                    console.warn(`Relasi ${relation} tidak ditemukan di response`, response);
                    return;
                }

                if (Array.isArray(relationData)) {
                    $.each(relationData, function (index, item) {
                        selectElement.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                } else if (typeof relationData === 'object' && relationData !== null) {
                    selectElement.append(`<option value="${relationData.id}">${relationData.name}</option>`);
                }

                selectElement.trigger('change'); // Refresh Select2 jika digunakan
            }
        },
        error: function (xhr, status, error) {
            console.error("Error Fetching Data:", xhr.responseText);
        }
    });
}

// Fungsi untuk mendapatkan properti bersarang secara dinamis
function getNestedProperty(obj, path) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}


function returnShowByRelation({
    url,                // Url request
    model,              // Nama model untuk request
    relation,           // Nama Relasi untuk request
    key,                // Kolom yang digunakan sebagai filter
    data,               // Nilai yang dikirimkan dalam request
}) {

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            model: model,
            key: key,
            relation: relation,
            data: data,
            is_collection: 0
        },
        success: function (response) {
            return response;
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function applyToSelect({
    dataOptions,
    affectSelectorId,
    optionPlaceholder
}) {

    let selectElement = $(affectSelectorId);
    selectElement.empty();
    selectElement.append(`<option value="">${optionPlaceholder}</option>`);

    if (Array.isArray(dataOptions)) {
        $.each(dataOptions, function (index, item) {
            selectElement.append(`<option value="${item.id}">${item.name}</option>`);
        });
    } else if (typeof dataOptions === 'object' && dataOptions !== null) {
        selectElement.append(`<option value="${dataOptions.id}">${dataOptions.name}</option>`);
    }

    selectElement.trigger('change'); // Refresh Select2 jika digunakan
}

// -----------------------------
// PDF
// -----------------------------

function previewPDF(url, token, bladePath, start_date = null, end_date = null) {
    // Buat dan tampilkan modal spinner
    showLoadingModal();
    const currentYear = new Date().getFullYear();
    let start = start_date ?? `${currentYear}-01-01`;
    let end = end_date ?? `${currentYear}-12-31`;
    const requestData = {
        _token: token,
        bladePath: bladePath,
        start_date: start,
        end_date: end,
    };

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.blob();  // Convert ke blob (PDF)
    })
    .then(blob => {
        const fileURL = URL.createObjectURL(blob);
        window.open(fileURL);  // Tampilkan PDF di tab baru
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memuat PDF. Silakan coba lagi.');
    })
    .finally(() => {
        // Hapus modal spinner setelah selesai
        hideLoadingModal();
    });
}

// Fungsi untuk membuat dan menampilkan modal spinner
function showLoadingModal() {
    // Cek apakah modal sudah ada
    if (!document.getElementById('loadingModal')) {
        const modal = document.createElement('div');
        modal.id = 'loadingModal';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
        modal.style.zIndex = '9999';

        const spinner = document.createElement('div');
        spinner.className = 'spinner-border text-danger';
        spinner.style.width = '5rem';
        spinner.style.height = '5rem';
        spinner.setAttribute('role', 'status');

        const spinnerText = document.createElement('span');
        spinnerText.className = 'visually-hidden';
        spinnerText.innerText = 'Loading...';

        spinner.appendChild(spinnerText);
        modal.appendChild(spinner);
        document.body.appendChild(modal);
    }
}

// Fungsi untuk menghapus modal spinner
function hideLoadingModal() {
    const modal = document.getElementById('loadingModal');
    if (modal) {
        document.body.removeChild(modal);
    }
}