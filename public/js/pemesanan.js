// === 1. DEKLARASI ELEMEN ===
const serviceSelect = document.getElementById("service_id");
const promoInput = document.getElementById("promo_code_input");
const promoBtn = document.getElementById("apply_promo_btn");
const promoMsg = document.getElementById("promo_message");
const bookingForm = document.getElementById("bookingForm");
const submitButton = document.getElementById("submit_button");

// Rincian Biaya
const rincianHarga = document.getElementById("rincian_harga");
const rincianDiskonRow = document.getElementById("rincian_diskon_row");
const rincianDiskon = document.getElementById("rincian_diskon");
const rincianTotal = document.getElementById("rincian_total");

// Pembayaran
const payTunai = document.getElementById("pay_tunai");
const payTransfer = document.getElementById("pay_transfer");
const payQris = document.getElementById("pay_qris");
const proofInput = document.getElementById("payment_proof");
const infoBoxTransfer = document.getElementById("payment_info_transfer");
const infoBoxQris = document.getElementById("payment_info_qris");
const displayPriceTransfer = document.getElementById("display_price_transfer");
const displayPriceQris = document.getElementById("display_price_qris");

// Slot Waktu
const datePicker = document.getElementById("date_picker");
const slotContainer = document.getElementById("slots_container");
const timeContainer = document.getElementById("slots_time_container");
const finalBookingDate = document.getElementById("final_booking_date");
const finalSlot = document.getElementById("final_slot"); // <-- Penting
const startHour = 9,
    endHour = 17;

// Modals
const confirmationModal = new bootstrap.Modal(
    document.getElementById("confirmationModal")
);

// Ambil data URL dan Token dari HTML
const scheduleUrl = bookingForm.dataset.scheduleUrl;
const checkPromoUrl = bookingForm.dataset.checkPromoUrl;
const csrfToken = bookingForm.dataset.csrfToken;

// --- UTILITY ---
const formatRupiah = (val) =>
    "Rp " + new Intl.NumberFormat("id-ID").format(val);

// === 2. FUNGSI UTAMA (UPDATE SEMUA HARGA) ===
async function updatePriceAndPromo(isPromoClick = false) {
    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    const serviceId = serviceSelect.value;
    const promoCode = promoInput.value.toUpperCase();
    const currentBasePrice =
        parseFloat(selectedOption.getAttribute("data-price")) || 0;

    if (!serviceId) {
        updateRincianBox(0, 0, 0, false);
        promoMsg.innerHTML = "Silakan pilih layanan terlebih dahulu.";
        promoMsg.className = "small mt-1 text-muted";
        promoBtn.disabled = true;
        return;
    }

    if (!promoCode || !isPromoClick) {
        updateRincianBox(currentBasePrice, 0, currentBasePrice, true);
        if (!isPromoClick) promoMsg.innerHTML = "";
        promoBtn.disabled = false;
        promoBtn.innerHTML = "Terapkan";
        return;
    }

    promoBtn.disabled = true;
    promoBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm"></span>';

    try {
        const response = await fetch(checkPromoUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                service_id: serviceId,
                promo_code: promoCode,
            }),
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            updateRincianBox(currentBasePrice, 0, currentBasePrice, true);
            promoMsg.innerHTML = data.message || "Kode promo tidak valid.";
            promoMsg.className = "small mt-1 text-danger";
        } else {
            updateRincianBox(data.base_price, data.discount, data.total, true);
            promoMsg.innerHTML = data.message;
            promoMsg.className = "small mt-1 text-success";
        }
    } catch (error) {
        updateRincianBox(currentBasePrice, 0, currentBasePrice, true);
        promoMsg.innerHTML = "Error kalkulasi. Harga normal digunakan.";
        promoMsg.className = "small mt-1 text-danger";
    } finally {
        promoBtn.disabled = false;
        promoBtn.innerHTML = "Terapkan";
    }
}

// Fungsi helper untuk update DOM
function updateRincianBox(base, discount, total, enableInputs) {
    const formattedTotal = formatRupiah(total);

    rincianHarga.innerText = formatRupiah(base);
    rincianTotal.innerText = formattedTotal;
    displayPriceTransfer.innerText = formattedTotal;
    displayPriceQris.innerText = formattedTotal;

    document.getElementById("final_base_price").value = base;
    document.getElementById("final_discount_amount").value = discount;
    document.getElementById("final_total_price").value = total;

    if (discount > 0) {
        rincianDiskon.innerText = "- " + formatRupiah(discount);
        rincianDiskonRow.style.display = "flex";
    } else {
        rincianDiskonRow.style.display = "none";
    }

    submitButton.disabled = !enableInputs;
}

// --- 3. LOGIKA MODAL KONFIRMASI (POP-UP) ---
submitButton.addEventListener("click", function (e) {
    e.preventDefault();

    if (!bookingForm.checkValidity()) {
        bookingForm.reportValidity();
        return;
    }

    const selectedTime = finalBookingDate.value;
    if (!selectedTime) {
        alert("Silakan pilih jam kedatangan terlebih dahulu.");
        document.getElementById("slots_time_container").style.border =
            "2px solid red";
        setTimeout(() => {
            document.getElementById("slots_time_container").style.border =
                "none";
        }, 3000);
        return;
    }

    const selectedService =
        serviceSelect.options[serviceSelect.selectedIndex].getAttribute(
            "data-name"
        );
    const selectedPayment = document.querySelector(
        'input[name="payment_method"]:checked'
    ).value;
    const selectedSlot = document.getElementById("final_slot").value;

    document.getElementById("modal_nama").innerText =
        document.getElementById("name").value;
    document.getElementById("modal_layanan").innerText =
        selectedService || "Belum dipilih";
    document.getElementById("modal_waktu").innerText = new Date(
        selectedTime
    ).toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" });
    document.getElementById("modal_slot").innerText =
        `Slot ${selectedSlot}` || "Belum dipilih"; // <-- Tampilkan Slot
    document.getElementById("modal_bayar").innerText = selectedPayment;

    document.getElementById("modal_harga_dasar").innerText =
        rincianHarga.innerText;
    document.getElementById("modal_diskon").innerText =
        rincianDiskon.innerText || "Rp 0";
    document.getElementById("modal_total_akhir").innerText =
        rincianTotal.innerText;

    document.getElementById("modal_diskon_row").style.display =
        rincianDiskonRow.style.display;

    confirmationModal.show();
});

document
    .getElementById("final_submit_btn")
    .addEventListener("click", function () {
        bookingForm.submit();
    });

// --- 4. LOGIKA PEMBAYARAN (PERBAIKAN QRIS/TRANSFER/TUNAI) ---
function togglePayment() {
    const method = document.querySelector(
        'input[name="payment_method"]:checked'
    ).value;

    infoBoxTransfer.classList.add("d-none");
    infoBoxQris.classList.add("d-none");

    proofInput.required = false;
    document.getElementById("payment_proof_qris").required = false;

    if (method === "Transfer") {
        infoBoxTransfer.classList.remove("d-none");
        proofInput.required = true;
    } else if (method === "QRIS") {
        infoBoxQris.classList.remove("d-none");
        document.getElementById("payment_proof_qris").required = true;
    }
}

document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
    radio.addEventListener("change", togglePayment);
});

// --- 5. LOGIKA SLOT WAKTU (AJAX) ---
function loadSlots() {
    const serviceId = serviceSelect.value;
    const date = datePicker.value;

    // Reset
    slotContainer.innerHTML =
        '<div class="text-muted fst-italic small">Silakan pilih layanan dan tanggal...</div>';
    timeContainer.innerHTML = "";
    finalBookingDate.value = "";
    finalSlot.value = "";

    if (!serviceId || !date) {
        return;
    }

    slotContainer.innerHTML =
        '<div class="spinner-border text-success spinner-border-sm" role="status"></div> Mencari slot...';

    fetch(`${scheduleUrl}?date=${date}&service_id=${serviceId}`)
        .then((response) => {
            if (!response.ok)
                throw new Error("Gagal memuat jadwal dari server.");
            return response.json();
        })
        .then((data) => {
            slotContainer.innerHTML = ""; // Bersihkan loading

            let hasAvailableSlot = false;
            for (let i = 1; i <= 4; i++) {
                const slotBtn = document.createElement("input");
                slotBtn.type = "radio";
                slotBtn.className = "btn-check";
                slotBtn.name = "slot_selection";
                slotBtn.id = `slot_${i}`;
                slotBtn.value = i;

                const hasSlots = data[i] && data[i].length > 0;
                if (hasSlots) {
                    slotBtn.onchange = () => displayTimeSlots(data[i]);
                    hasAvailableSlot = true;
                } else {
                    slotBtn.disabled = true;
                }

                const labelBtn = document.createElement("label");
                labelBtn.className =
                    "btn " +
                    (hasSlots
                        ? "btn-outline-success"
                        : "btn-outline-secondary disabled");
                labelBtn.htmlFor = `slot_${i}`;
                labelBtn.innerText = `Slot ${i}`;

                if (!hasSlots) {
                    labelBtn.innerText += " (Penuh)";
                }

                slotContainer.appendChild(slotBtn);
                slotContainer.appendChild(labelBtn);
            }
            if (!hasAvailableSlot) {
                slotContainer.innerHTML =
                    '<div class="alert alert-warning w-100">Tidak ada slot tersedia untuk layanan/tanggal ini.</div>';
            }
        })
        .catch((error) => {
            console.error("Error fetching schedule:", error);
            slotContainer.innerHTML =
                '<div class="alert alert-danger w-100">Gagal memuat jadwal.</div>';
        });
}

// Fungsi untuk menampilkan jam setelah slot dipilih
function displayTimeSlots(availableTimes) {
    timeContainer.innerHTML = "";
    finalBookingDate.value = "";
    finalSlot.value = document.querySelector(
        'input[name="slot_selection"]:checked'
    ).value;

    if (availableTimes.length === 0) {
        timeContainer.innerHTML =
            '<div class="text-muted w-100 text-center">Tidak ada jam tersedia di slot ini.</div>';
        return;
    }

    availableTimes.forEach((time) => {
        const timeBtn = document.createElement("input");
        timeBtn.type = "radio";
        timeBtn.className = "btn-check";
        timeBtn.name = "time_selection";
        timeBtn.id = `time_${time}`;
        timeBtn.value = time;

        timeBtn.onchange = () => {
            finalBookingDate.value = `${datePicker.value} ${time}:00`;
        };

        const labelBtn = document.createElement("label");
        labelBtn.className = "btn btn-outline-success time-slot-btn";
        labelBtn.htmlFor = `time_${time}`;
        labelBtn.innerText = time;

        timeContainer.appendChild(timeBtn);
        timeContainer.appendChild(labelBtn);
    });
}

// === 6. EVENT LISTENERS & INISIALISASI HALAMAN ===
serviceSelect.addEventListener("change", () => {
    updatePriceAndPromo(false);
    loadSlots();
});
promoBtn.addEventListener("click", () => updatePriceAndPromo(true));
promoInput.addEventListener("input", () => {
    if (promoInput.value === "") {
        updatePriceAndPromo(false);
    }
});

datePicker.addEventListener("change", () => {
    loadSlots();
    finalBookingDate.value = "";
});

document.addEventListener("DOMContentLoaded", () => {
    loadSlots();
    updatePriceAndPromo();
    togglePayment();
});
