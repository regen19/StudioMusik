@auth
@php
  $unread = auth()->user()->unreadNotifications()->limit(10)->get();
@endphp

<div class="dropdown" id="notifDropdown">
  <button
    id="btnNotif"
    type="button"
    class="btn p-0 border-0 bg-transparent dropdown-toggle position-relative"
    data-bs-toggle="dropdown"
    data-bs-auto-close="outside"
    aria-expanded="false"
    aria-label="Notifikasi"
  >
    <i class="bi bi-bell fs-5"></i>

    @if($unread->count())
      <span
        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
        style="pointer-events:none"
      >
        {{ $unread->count() }}
      </span>
    @endif
  </button>

  <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="btnNotif" style="min-width:320px">
    <div class="list-group list-group-flush">
      @forelse($unread as $n)
        <div class="list-group-item">
          <div class="fw-semibold">{{ $n->data['title'] ?? 'Notifikasi' }}</div>
          <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
        </div>
      @empty
        <div class="p-3 text-muted">Tidak ada notifikasi baru</div>
      @endforelse
    </div>
    <div class="p-2 border-top text-end">
      <form action="{{ route('notifications.readAll') }}" method="post" class="d-inline">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">Tandai sudah dibaca</button>
      </form>
    </div>
  </div>
</div>
@endauth