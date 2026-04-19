@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container py-4" style="max-width:680px">
    <h1 class="fs-4 fw-bold mb-4">
        <i class="bi bi-pencil-square text-primary me-2"></i>Modifier mon profil
    </h1>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Avatar --}}
                <div class="mb-4 text-center">
                    <img src="{{ $user->avatar_url }}" id="avatarPreview"
                         class="rounded-circle shadow mb-2" width="90" height="90" style="object-fit:cover">
                    <div>
                        <label for="avatarInput" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-camera me-1"></i>Changer la photo
                        </label>
                        <input type="file" name="avatar" id="avatarInput" class="d-none"
                               accept="image/jpeg,image/png,image/webp"
                               onchange="previewAvatar(this)">
                        @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Nom complet</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Téléphone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="06XXXXXXXX">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Ville</label>
                        <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Biographie</label>
                        <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Quelques mots sur vous…" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-save me-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
