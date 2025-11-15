document.addEventListener('DOMContentLoaded', function() {
    const slotInfoModal = document.getElementById('slotInfoModal');
    if (slotInfoModal) {
        slotInfoModal.addEventListener('show.bs.modal', function(event) {
            // Tombol yang memicu modal
            const button = event.relatedTarget;

            // Ambil data slotId dari tombol
            const slotId = button.getAttribute('data-slot-id');

            // Update judul modal
            const modalTitle = slotInfoModal.querySelector('.modal-title');
            modalTitle.textContent = `Jadwal Booking Hari Ini: Slot ${slotId}`;

            // Ambil elemen body modal
            const timelineBody = slotInfoModal.querySelector('#scheduleTimeline');
            timelineBody.innerHTML = ''; // Kosongkan

            // Ambil data jadwal untuk slot ini (dari variabel global yg dikirim PHP)
            const bookingsOnThisSlot = jadwalSlots[slotId] || [];

            let slotTersedia = 0;

            // Loop dari jam buka sampai jam tutup
            for (let i = jamBuka; i < jamTutup; i++) {
                const timeString = (i < 10 ? '0' + i : i) + ':00';

                // Cek apakah jam ini sudah lewat
                const isPassed = timeString <= jamSekarang;

                // Cek apakah jam ini ada di daftar booking
                // Kita pakai find() untuk cek lebih detail
                const booking = bookingsOnThisSlot.find(tx => tx.booking_date.includes(timeString));

                let statusText = '';
                let statusClass = '';
                let serviceName = 'Tersedia';

                if (booking) {
                    statusText = 'Dipesan';
                    statusClass = 'status-booked'; // Merah
                    serviceName = booking.service.name;
                } else if (isPassed) {
                    statusText = 'Waktu Lewat';
                    statusClass = 'status-passed'; // Abu-abu
                } else {
                    statusText = 'Tersedia';
                    statusClass = 'status-available'; // Hijau
                    slotTersedia++;
                }

                // Buat elemen HTML
                const li = document.createElement('li');
                li.className = 'list-group-item schedule-item';
                li.innerHTML = `
                    <div class="schedule-time">${timeString}</div>
                    <div class="schedule-info">
                        <strong>${serviceName}</strong>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </div>
                `;
                timelineBody.appendChild(li);
            }

            if (slotTersedia === 0 && bookingsOnThisSlot.length === 0) {
                 timelineBody.innerHTML = '<li class="list-group-item text-muted text-center">Tidak ada jadwal tersedia atau slot penuh.</li>';
            }
        });
    }
});
