<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    {{-- Kita gunakan Alpine.js (bawaan Breeze) untuk membuat Tabs --}}
    <div class="py-12" x-data="{ tab: 'riwayat' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tab Navigation --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    {{-- Tombol Tab 1: Riwayat (Default) --}}
                    <li class="mr-2" role="presentation">
                        <button @click.prevent="tab = 'riwayat'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'riwayat', 'border-transparent hover:text-gray-600 hover:border-gray-300': tab !== 'riwayat' }"
                                class="inline-block p-4 border-b-2 rounded-t-lg text-base"
                                id="riwayat-tab" type="button">
                            <i class="fa fa-history me-2"></i>Riwayat Transaksi
                        </button>
                    </li>
                    {{-- Tombol Tab 2: Pengaturan Akun --}}
                    <li class="mr-2" role="presentation">
                        <button @click.prevent="tab = 'pengaturan'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'pengaturan', 'border-transparent hover:text-gray-600 hover:border-gray-300': tab !== 'pengaturan' }"
                                class="inline-block p-4 border-b-2 rounded-t-lg text-base"
                                id="pengaturan-tab" type="button">
                            <i class="fa fa-cog me-2"></i>Pengaturan Akun
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div>
                {{-- ========================================================== --}}
                {{-- KONTEN TAB 1: RIWAYAT TRANSAKSI (Dengan Alert Baru) --}}
                {{-- ========================================================== --}}
                <div x-show="tab === 'riwayat'" id="riwayat" class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Riwayat Transaksi & Booking Saya
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Pantau status booking Anda atau lihat riwayat cucian sebelumnya.
                            </p>

                            <div class="mt-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jadwal/Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($transactions as $tx)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $tx->booking_date ? \Carbon\Carbon::parse($tx->booking_date)->format('d M Y, H:i') : $tx->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tx->service->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">

                                                {{-- === PERBAIKAN LOGIKA STATUS DENGAN ALERT === --}}
                                                @if($tx->status == 'Terkonfirmasi')
                                                    <div class="p-3 rounded-md bg-blue-50 border border-blue-200">
                                                        <p class="font-bold text-blue-800"><i class="fa fa-check-circle me-2"></i>Pesanan Terkonfirmasi</p>
                                                        <p class="text-blue-700 text-xs">Silakan datang ke tempat kami sesuai jadwal.</p>
                                                    </div>
                                                @elseif($tx->status == 'Selesai' || $tx->status == 'Sudah Dibayar')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                @elseif($tx->status == 'Sedang Dicuci')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sedang Dicuci</span>
                                                @elseif($tx->status == 'Ditolak')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak (Pembayaran Gagal)</span>
                                                @elseif($tx->status == 'Menunggu')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Menunggu Verifikasi</span>
                                                @endif
                                                {{-- ========================================== --}}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Anda belum memiliki riwayat transaksi.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================================== --}}
                {{-- KONTEN TAB 2: PENGATURAN AKUN --}}
                {{-- ========================================================== --}}
                <div x-show="tab === 'pengaturan'" id="pengaturan" class="space-y-6">
                    {{-- Form Update Info Profil --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- Form Update Password --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Form Hapus Akun --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
