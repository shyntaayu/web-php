const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let debounceTimer;

// Fungsi untuk render tabel hasil
function renderHasil(mahasiswas, total) {
    // 1. Targetkan T-BODY dari tabel #hasil
    const tbody = document.querySelector("#hasil tbody");
    const statusText = document.getElementById("status");

    // 2. Jika data kosong
    if (!mahasiswas.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data ditemukan.</td>
            </tr>`;
        statusText.textContent = "Data tidak ditemukan.";
        return;
    }

    // 3. Update teks status
    statusText.textContent = `Ditemukan ${total} mahasiswa`;

    // 4. Looping data untuk membuat baris (<tr>) baru
    let html = "";
    mahasiswas.forEach((mhs, index) => {
        // Ambil IPK dan format menjadi 2 desimal jika ada
        let ipk = mhs.ipk ? parseFloat(mhs.ipk).toFixed(2) : "-";
        // Ambil jurusan (atau prodi, sesuaikan dengan output JSON dari API Anda)
        let jurusan = mhs.jurusan || mhs.prodi || "-";

        html += `
            <tr>
                <td>${index + 1}</td>
                <td>${mhs.nim}</td>
                <td>${mhs.nama}</td>
                <td>${jurusan}</td>
                <td>${ipk}</td>
                <td>
                    <a href="/mahasiswa/${mhs.id}/edit">Edit</a>
                    <form action="/mahasiswa/${mhs.id}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus data ini?');">
                        <input type="hidden" name="_token" value="${CSRF}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>`;
    });

    // 5. Masukkan HTML baru ke dalam tabel
    tbody.innerHTML = html;
}

// Fungsi pencarian
async function cariMahasiswa(keyword) {
    document.getElementById("loading").classList.remove("hidden");
    const tbody = document.querySelector("#hasil tbody");

    // Opsional: Kosongkan tabel saat mencari
    // tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Mencari...</td></tr>';

    try {
        const url = keyword
            ? `/api/mahasiswa?q=${encodeURIComponent(keyword)}`
            : "/api/mahasiswa";
        const res = await fetch(url, {
            headers: { Accept: "application/json", "X-CSRF-TOKEN": CSRF },
        });

        if (!res.ok) throw new Error(`Error ${res.status}`);

        const json = await res.json();
        renderHasil(json.data, json.total);
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:red;">Gagal: ${err.message}</td></tr>`;
    } finally {
        document.getElementById("loading").classList.add("hidden");
    }
}

// Debounce: tunda request 400ms setelah berhenti mengetik
document.getElementById("search").addEventListener("input", function () {
    const keyword = this.value.trim();
    document.getElementById("status").textContent = "Menunggu input...";

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => cariMahasiswa(keyword), 400);
});

// Load data awal tidak perlu dipanggil jika Anda ingin menampilkan
// data bawaan Blade (forelse) saat halaman pertama kali dibuka.
// Jika ingin API yang ambil alih sepenuhnya, biarkan baris di bawah aktif:
// cariMahasiswa("");
