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
const slotsContainer = document.getElementById("slots_container");
const finalBookingDate = document.getElementById("final_booking_date");
const startHour = 9,
    endHour = 17;

// Modals
const confirmationModal = new bootstrap.Modal(
    document.getElementById("confirmationModal")
);

// Ambil data URL dan Token dari HTML
const checkPromoUrl = bookingForm.dataset.checkPromoUrl;
const checkSlotsUrl = bookingForm.dataset.checkSlotsUrl;
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
        document.getElementById("slots_container").style.border =
            "2px solid red";
        setTimeout(() => {
            document.getElementById("slots_container").style.border = "none";
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

    document.getElementById("modal_nama").innerText =
        document.getElementById("name").value;
    document.getElementById("modal_layanan").innerText =
        selectedService || "Belum dipilih";
    document.getElementById("modal_waktu").innerText = new Date(
        selectedTime
    ).toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" });
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

    // Sembunyikan semua boks
    infoBoxTransfer.classList.add("d-none");
    infoBoxQris.classList.add("d-none");

    // Matikan 'required' untuk upload bukti
    proofInput.required = false;
    document.getElementById("payment_proof_qris").required = false;

    if (method === "Transfer") {
        infoBoxTransfer.classList.remove("d-none");
        proofInput.required = true; // Wajib untuk transfer
    } else if (method === "QRIS") {
        infoBoxQris.classList.remove("d-none");
        document.getElementById("payment_proof_qris").required = true;
    }
    // Jika 'Tunai', tidak ada boks yang muncul
}

// Tambahkan event listener ke semua radio button
document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
    radio.addEventListener("change", togglePayment);
});

// Panggil sekali saat load (SANGAT PENTING)
document.addEventListener("DOMContentLoaded", () => {
    // ... (kode loadSlots dan updatePrice Anda) ...
    togglePayment(); // Panggil ini saat inisialisasi
});

// --- 5. LOGIKA SLOT WAKTU (AJAX) ---
function loadSlots() {
    const selectedDate = datePicker.value;
    if (!selectedDate) return;

    slotsContainer.innerHTML =
        '<div class="spinner-border text-success spinner-border-sm" role="status"></div> Memuat jadwal...';
    finalBookingDate.value = "";

    fetch(`${checkSlotsUrl}?date=${selectedDate}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            slotsContainer.innerHTML = "";

            const bookedSlots = data.booked || [];
            const isToday = data.is_today || false;
            const currentTime = data.current_time || "00:00";

            let availableSlots = 0;

            for (let i = startHour; i <= endHour; i++) {
                const hour = i < 10 ? "0" + i : i;
                const timeString = `${hour}:00`;

                const isBooked = bookedSlots.includes(timeString);
                const isPassed = isToday && timeString <= currentTime;

                const btn = document.createElement("button");
                btn.type = "button";
                let btnClass = "btn flex-grow-1 ";

                if (isBooked) {
                    btnClass += "btn-secondary opacity-50";
                } else if (isPassed) {
                    btnClass += "btn-light text-muted border";
                } else {
                    btnClass += "btn-outline-success"; // Hijau
                    availableSlots++;
                }
                btn.className = btnClass;
                btn.innerText = timeString;
                btn.style.minWidth = "80px";

                if (isBooked || isPassed) {
                    btn.disabled = true;
                    btn.title = isBooked
                        ? "Sudah dibooking"
                        : "Waktu sudah lewat";
                } else {
                    btn.onclick = () => selectSlot(btn, timeString);
                }
                slotsContainer.appendChild(btn);
            }

            if (availableSlots === 0 && slotsContainer.innerHTML === "") {
                slotsContainer.innerHTML =
                    '<div class="alert alert-warning w-100">Mohon maaf, tidak ada slot yang tersedia untuk tanggal ini.</div>';
            }
        })
        .catch((error) => {
            console.error("Error saat loadSlots:", error);
            slotsContainer.innerHTML =
                '<span class="text-danger small">Gagal memuat jadwal. Cek konsol browser (F12).</span>';
        });
}

function selectSlot(btn, time) {
    slotsContainer.querySelectorAll("button").forEach((b) => {
        if (!b.disabled) b.className = "btn btn-outline-success flex-grow-1";
    });
    btn.className = "btn btn-success flex-grow-1";
    finalBookingDate.value = datePicker.value + " " + time + ":00";
}

// === 6. EVENT LISTENERS & INISIALISASI HALAMAN ===
serviceSelect.addEventListener("change", () => updatePriceAndPromo(false));
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
