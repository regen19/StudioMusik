@extends('partials.main')

@section('MainContent')
  @include('user.jadwal_alat_usr.md_add_pinjam_alat_usr', ['alat' => $alat])
@endsection

@push('script')
    <script>
            document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('add_pinjam_alat');
            if (el) bootstrap.Modal.getOrCreateInstance(el).show();
        });
    </script>
@endpush