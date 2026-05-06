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
        if (filter === "Selesai" && item.status === "Selesai") return true;
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
      if (item.status === "Sedang Berlangsung") badgeClass = "status-sedang";
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
