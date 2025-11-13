<div class="card kanban-card mb-3">
    <div class="card-body">
        <h5 class="card-title">{{ $tx->customer->license_plate ?? 'N/A' }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ $tx->customer->name ?? 'N/A' }}</h6>
        <p class="card-text mb-1">
            <i class="fa fa-car"></i> {{ $tx->customer->vehicle_type ?? 'Mobil' }}
        </p>
        <p class="card-text">
            <i class="fa fa-concierge-bell"></i> {{ $tx->service->name ?? 'Layanan' }}
        </p>

        <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm {{ $action_class }} w-100">
                {{ $action_text }}
            </button>
        </form>

        <a href="{{ route('pos.struk', $tx->id) }}" class="btn btn-sm btn-outline-secondary w-100 mt-2" target="_blank">
            Lihat Struk
        </a>
    </div>
    <div class="card-footer text-muted text-center" style="font-size: 0.8rem;">
        No: {{ $tx->transaction_code }}
    </div>
</div>
