// 1. Inisialisasi Data dari LocalStorage
let jadwalData = JSON.parse(localStorage.getItem("sijam_data")) || [];
let editId = null;

// DOM Elements
const form = document.getElementById("form-matkul");
const inputMatkul = document.getElementById("mata-kuliah");
const inputStatus = document.getElementById("status");
const tableBody = document.querySelector(".data-table tbody");
const tableHeadRow = document.querySelector(".data-table thead tr");
const searchInput = document.querySelector(".search-input");
const filterCheckboxes = document.querySelectorAll(".custom-checkbox input");

// --- SETUP AWAL DOM MANIPULATION ---
// Menambahkan kolom 'Aksi' pada tabel header jika belum ada
if (!document.getElementById("th-aksi")) {
    const thAksi = document.createElement("th");
    thAksi.id = "th-aksi";
    thAksi.innerText = "Aksi";
    tableHeadRow.appendChild(thAksi);
}

// Menambahkan input 'Jam' dan 'Ruangan' secara dinamis ke form
const formGrid = document.querySelector(".form-grid");
const submitBtn = formGrid.querySelector(".btn-submit");

if (!document.getElementById("jam")) {
    const jamGroup = document.createElement("div");
    jamGroup.className = "form-group";
    jamGroup.innerHTML = `
        <label>Jam</label>
        <input type="text" class="form-input" id="jam" placeholder="Contoh: 08:00 - 10:00">
        <span class='error' id='error-jam' style="color:red; font-size:0.8rem; display:block; margin-top:5px;"></span>
    `;
    formGrid.insertBefore(jamGroup, submitBtn);
}

if (!document.getElementById("ruangan")) {
    const ruanganGroup = document.createElement("div");
    ruanganGroup.className = "form-group";
    ruanganGroup.innerHTML = `
        <label>Ruangan</label>
        <input type="text" class="form-input" id="ruangan" placeholder="Contoh: R.302">
        <span class='error' id='error-ruangan' style="color:red; font-size:0.8rem; display:block; margin-top:5px;"></span>
    `;
    formGrid.insertBefore(ruanganGroup, submitBtn);
}

// Styling error default agar rapi
document.getElementById("error-mata-kuliah").style.cssText =
    "color:red; font-size:0.8rem; display:block; margin-top:5px;";
document.getElementById("error-status").style.cssText =
    "color:red; font-size:0.8rem; display:block; margin-top:5px;";

// --- FUNGSI UTAMA ---

// Menyimpan data ke localStorage
const saveData = () => {
    localStorage.setItem("sijam_data", JSON.stringify(jadwalData));
};

// 7. Update Statistik Dasbor
const updateStats = () => {
    const statCards = document.querySelectorAll(".stat-card p");
    if (statCards.length >= 4) {
        const totalMatkul = jadwalData.length;
        const sedangJalan = jadwalData.filter(
            (item) => item.status === "Sedang Berlangsung",
        ).length;
        const akanDiajar = jadwalData.filter(
            (item) => item.status === "Akan Diajar",
        ).length;
        const selesai = jadwalData.filter(
            (item) => item.status === "Selesai",
        ).length;

        statCards[0].innerHTML = `<strong>${totalMatkul}</strong> Total Jadwal`;
        statCards[1].innerHTML = `<strong>${sedangJalan}</strong> Sedang Jalan`;
        statCards[2].innerHTML = `<strong>${akanDiajar}</strong> Akan Datang`;
        statCards[3].innerHTML = `<strong>${selesai}</strong> Selesai`;
    }
};

// 2. Render Tabel dengan Filter dan Pencarian
const renderTable = (searchQuery = "") => {
    // 1. Ambil nilai (value) dari checkbox yang sedang dicentang
    const activeFilters = Array.from(filterCheckboxes)
        .filter((cb) => cb.checked)
        .map((cb) => cb.value); // Ini akan mengambil "Semua", "Aktif", "Selesai", dll

    // 2. Filter Data
    const filteredData = jadwalData.filter((item) => {
        // Logika Pencarian Teks
        const matchSearch = item.matkul
            .toLowerCase()
            .includes(searchQuery.toLowerCase());

        // Logika Filter Checkbox
        let matchFilter = false; // Default false jika ada filter spesifik yang menyala

        // Jika "Semua" dicentang ATAU tidak ada sama sekali yang dicentang -> loloskan semua
        if (activeFilters.includes("Semua") || activeFilters.length === 0) {
            matchFilter = true;
        } else {
            // Jika filter spesifik dicentang, cocokkan dengan status item
            matchFilter = activeFilters.some((filter) => {
                if (
                    filter === "Aktif" &&
                    (item.status === "Akan Diajar" ||
                        item.status === "Sedang Berlangsung")
                )
                    return true;
                if (filter === "Selesai" && item.status === "Selesai")
                    return true;
                if (filter === "Dibatalkan" && item.status === "Dibatalkan")
                    return true;
                return false;
            });
        }

        return matchSearch && matchFilter;
    });

    // 3. Render HTML ke dalam Tabel
    tableBody.innerHTML = filteredData
        .map((item) => {
            let badgeClass = "status-akan";
            if (item.status === "Selesai") badgeClass = "status-selesai";
            if (item.status === "Sedang Berlangsung")
                badgeClass = "status-sedang";
            if (item.status === "Dibatalkan") badgeClass = "status-batal";

            return `
            <tr>
                <td>${item.matkul}</td>
                <td>${item.jam}</td>
                <td>${item.ruangan}</td>
                <td><span class="badge ${badgeClass}">${item.status}</span></td>
                <td>
                    <button class="btn-edit" data-id="${item.id}" style="padding:6px 12px; cursor:pointer; margin-right:5px; background:#f39c12; color:white; border:none; border-radius:4px;">Edit</button>
                    <button class="btn-hapus" data-id="${item.id}" style="padding:6px 12px; cursor:pointer; background:#e74c3c; color:white; border:none; border-radius:4px;">Hapus</button>
                </td>
            </tr>
        `;
        })
        .join("");

    // Panggil update statistik setiap kali tabel dirender
    updateStats();
};

// 1. Form Submit & Validasi Kustom
form.addEventListener("submit", (e) => {
    e.preventDefault(); // Mencegah reload halaman

    const matkul = inputMatkul.value.trim();
    const status = inputStatus.value;
    const jam = document.getElementById("jam").value.trim();
    const ruangan = document.getElementById("ruangan").value.trim();

    // Validasi Wajib Isi
    let isValid = true;
    const showError = (id, message) =>
        (document.getElementById(`error-${id}`).innerText = message);
    const clearError = (id) =>
        (document.getElementById(`error-${id}`).innerText = "");

    if (!matkul) {
        showError("mata-kuliah", "Mata Kuliah wajib diisi!");
        isValid = false;
    } else {
        clearError("mata-kuliah");
    }
    if (!jam) {
        showError("jam", "Jam wajib diisi! (Cth: 08:00 - 10:00)");
        isValid = false;
    } else {
        clearError("jam");
    }
    if (!ruangan) {
        showError("ruangan", "Ruangan wajib diisi!");
        isValid = false;
    } else {
        clearError("ruangan");
    }
    if (!status) {
        showError("status", "Status wajib dipilih!");
        isValid = false;
    } else {
        clearError("status");
    }

    if (!isValid) return; // Hentikan eksekusi jika validasi gagal

    if (editId) {
        // 3. Update Data (Fitur Edit)
        const index = jadwalData.findIndex((item) => item.id === editId);
        if (index !== -1) {
            jadwalData[index] = {
                ...jadwalData[index],
                matkul,
                jam,
                ruangan,
                status,
            };
        }
        editId = null;
        submitBtn.innerText = "Simpan Jadwal"; // Kembalikan teks tombol
    } else {
        // Tambah Data Baru
        const newItem = {
            id: Date.now().toString(), // ID unik menggunakan timestamp
            matkul,
            jam,
            ruangan,
            status,
        };
        jadwalData.push(newItem);
    }

    saveData();
    renderTable(searchInput.value);
    form.reset(); // Kosongkan form
});

// Event Delegation untuk Tombol Edit & Hapus di dalam Tabel
tableBody.addEventListener("click", (e) => {
    const id = e.target.dataset.id;

    // 3. Fitur Edit: Isi form dengan data yang diklik
    if (e.target.classList.contains("btn-edit")) {
        const item = jadwalData.find((d) => d.id === id);
        if (item) {
            inputMatkul.value = item.matkul;
            inputStatus.value = item.status;
            document.getElementById("jam").value = item.jam;
            document.getElementById("ruangan").value = item.ruangan;

            editId = item.id;
            submitBtn.innerText = "Update Jadwal";
            window.scrollTo({ top: 0, behavior: "smooth" }); // Auto-scroll ke form atas
        }
    }

    // 4. Fitur Hapus dengan Konfirmasi
    if (e.target.classList.contains("btn-hapus")) {
        if (confirm("Apakah Anda yakin ingin menghapus jadwal ini?")) {
            jadwalData = jadwalData.filter((item) => item.id !== id);
            saveData();
            renderTable(searchInput.value);
        }
    }
});

// 5. Fitur Pencarian Real-Time
searchInput.addEventListener("input", (e) => {
    renderTable(e.target.value);
});

// Mencegah form pencarian merefresh halaman jika tombol Enter ditekan
document.querySelector(".search-form").addEventListener("submit", (e) => {
    e.preventDefault();
});

// 6. Event Listener untuk Checkbox Filter Status (Dengan Logika Interaktif)
filterCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", (e) => {
        // Jika user mencentang "Semua", hilangkan centang di kategori lain
        if (e.target.value === "Semua" && e.target.checked) {
            filterCheckboxes.forEach((cb) => {
                if (cb.value !== "Semua") cb.checked = false;
            });
        }
        // Jika user mencentang kategori spesifik (misal: Aktif), hilangkan centang di "Semua"
        else if (e.target.value !== "Semua" && e.target.checked) {
            document.getElementById("filter-semua").checked = false;
        }

        // Panggil ulang render tabel
        renderTable(searchInput.value);
    });
});

// --- INIT APP (Saat pertama kali di-load) ---
// Masukkan data dummy jika localStorage kosong untuk keperluan demonstrasi
if (jadwalData.length === 0) {
    jadwalData = [
        {
            id: "1",
            matkul: "Kecerdasan Buatan",
            jam: "08:00 - 10:00",
            ruangan: "R.302",
            status: "Selesai",
        },
        {
            id: "2",
            matkul: "Struktur Data",
            jam: "10:30 - 12:00",
            ruangan: "Lab Jaringan",
            status: "Sedang Berlangsung",
        },
        {
            id: "3",
            matkul: "Basis Data",
            jam: "13:00 - 15:00",
            ruangan: "R.101",
            status: "Akan Diajar",
        },
    ];
    saveData();
}

// Panggil renderTable pertama kali agar tabel muncul
renderTable();

console.log("Aplikasi SIJAM siap digunakan!");
// --- FETCH API DATA UNIVERSITAS ---
const loadUniversitasData = () => {
    const tbodyUniv = document.getElementById("tbody-universitas");

    console.log("Mulai fetch data API...");

    fetch("http://universities.hipolabs.com/search?country=Indonesia")
        .then((response) => response.json())
        .then((data) => {
            console.log("Data diterima:", data);

            // Batasi hanya menampilkan 10 data agar tabel tidak terlalu panjang
            const limitedData = data.slice(0, 10);

            tbodyUniv.innerHTML = limitedData
                .map(
                    (univ, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${univ.name}</td>
            <td>
                <a href="${univ.web_pages[0]}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                    Kunjungi Website
                </a>
            </td>
        </tr>
      `,
                )
                .join("");
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
            tbodyUniv.innerHTML = `<tr><td colspan="3" style="text-align: center; color: red;">Gagal memuat data API.</td></tr>`;
        });

    console.log("Saya tidak menunggu fetch selesai!");
};

// Panggil fungsi saat aplikasi dimuat
loadUniversitasData();
document.addEventListener("DOMContentLoaded", () => {
    console.log("HTML sudah siap, memanggil API...");
    loadUniversitasData();
});

async function ambilCuaca() {
    try {
        // 'await' menjeda eksekusi HANYA di dalam fungsi ini
        // Browser tetap responsif untuk hal lain
        const response = await fetch(
            `https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=35.09.21.1002`,
        );

        // Cek apakah response HTTP-nya OK (200-299)
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

        // Parse JSON (ini juga asinkronus!)
        const data = await response.json();

        return data;
    } catch (error) {
        // Tangkap error network atau throw di atas
        console.error("Gagal ambil data:", error.message);
        throw error; // lempar ulang agar pemanggil bisa handle
    }
}

// 2. Fungsi UI (Untuk memproses data dari fungsi Anda ke HTML)
async function tampilkanCuaca() {
    const container = document.getElementById("cuaca-container");
    const lokasiEl = document.getElementById("lokasi-cuaca");

    try {
        // Tampilkan loading state
        container.innerHTML = `<div style="width: 100%; text-align: center; color: #6b7280;">Mengambil data dari BMKG... ⏳</div>`;

        // Memanggil fungsi ambilCuaca() milik Anda!
        const json = await ambilCuaca();

        // Tampilkan Lokasi
        if (json.lokasi) {
            const desa = json.lokasi.desa || "";
            const kecamatan = json.lokasi.kecamatan || "";
            lokasiEl.innerText = `${desa}, Kec. ${kecamatan}`;
        }

        // Ekstrak dan Ratakan Data Cuaca BMKG
        let dataCuaca = [];
        if (json.data && Array.isArray(json.data)) {
            json.data.forEach((hari) => {
                if (hari.cuaca && Array.isArray(hari.cuaca)) {
                    hari.cuaca.forEach((jam) => {
                        if (Array.isArray(jam)) dataCuaca.push(...jam);
                        else dataCuaca.push(jam);
                    });
                }
            });
        }

        // Ambil 8 waktu ke depan (untuk 24 jam)
        const cuacaHariIni = dataCuaca.slice(0, 8);

        // Cetak Card Cuaca ke HTML
        if (cuacaHariIni.length > 0) {
            container.innerHTML = cuacaHariIni
                .map((item) => {
                    const waktu =
                        item.local_datetime || item.datetime || item.waktu;
                    const dateObj = new Date(waktu.replace(" ", "T") + "Z");

                    const jam = dateObj.toLocaleTimeString("id-ID", {
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                    const tanggal = dateObj.toLocaleDateString("id-ID", {
                        day: "numeric",
                        month: "short",
                    });

                    const suhu = item.t || item.tcc || item.suhu || "-";
                    const deskripsi =
                        item.weather_desc || item.cuaca || "Berawan";
                    const iconSrc = item.image || item.icon || "";
                    const kelembapan = item.hu || item.kelembapan || "-";

                    return `
                    <div style="min-width: 130px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; flex-shrink: 0;">
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0; font-weight: 600;">${tanggal}</p>
                        <p style="font-size: 1.1rem; color: #0f172a; margin: 5px 0 10px 0; font-weight: bold;">${jam}</p>
                        
                        ${
                            iconSrc
                                ? `<img src="${iconSrc}" alt="${deskripsi}" style="width: 50px; height: 50px; margin: 0 auto; object-fit: contain;">`
                                : `<div style="font-size: 2rem; margin: 10px 0;">🌤️</div>`
                        }
                        
                        <p style="font-size: 1.3rem; font-weight: 700; color: #3b82f6; margin: 5px 0;">${suhu}°C</p>
                        <p style="font-size: 0.8rem; color: #475569; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${deskripsi}">${deskripsi}</p>
                        
                        <div style="display: flex; justify-content: center; align-items: center; gap: 4px; margin-top: 10px; font-size: 0.75rem; color: #64748b; background: #e2e8f0; border-radius: 4px; padding: 3px 0;">
                            <span>💧 ${kelembapan}%</span>
                        </div>
                    </div>
                `;
                })
                .join("");
        } else {
            container.innerHTML = `<div style="width: 100%; text-align:center; color: #ef4444;">Data cuaca kosong.</div>`;
        }
    } catch (error) {
        // Jika fungsi ambilCuaca() melempar error, akan ditangkap di sini
        container.innerHTML = `<div style="width: 100%; text-align:center; color: #ef4444;">Gagal memuat cuaca: ${error.message}</div>`;
    }
}

// 3. Jalankan saat halaman siap
document.addEventListener("DOMContentLoaded", () => {
    tampilkanCuaca();
});

const btn = document.getElementById("toggle-tema");
const ikon = document.getElementById("ikon-tema");
const html = document.documentElement;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Inisialisasi ikon berdasarkan tema saat ini
function updateIkon() {
    ikon.textContent = html.classList.contains("dark") ? "☀️" : "🌙";
}
updateIkon();

// Toggle tema saat tombol diklik
btn.addEventListener("click", async () => {
    const isDark = html.classList.toggle("dark");
    const temaBaru = isDark ? "dark" : "light";
    updateIkon();

    // Simpan ke cookie via server (opsional, jika ingin server tahu tema)
    try {
        await fetch("/preferensi/simpan", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": CSRF,
            },
            body: JSON.stringify({ tema: temaBaru, bahasa: "id" }),
        });
    } catch (e) {
        // Fallback: simpan langsung di cookie browser
        const exp = new Date(
            Date.now() + 365 * 24 * 60 * 60 * 1000,
        ).toUTCString();
        document.cookie = `tema=${temaBaru};expires=${exp};path=/;SameSite=Lax`;
    }
});
