{{-- Shared form partial for create and edit --}}

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Title --}}
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3">Informations principales</h5>
        <div class="mb-3">
            <label class="form-label fw-medium">Titre de l'annonce <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $announcement?->title) }}"
                   placeholder="Ex: iPhone 15 Pro Max 256GB – Comme neuf" maxlength="150">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Description <span class="text-danger">*</span></label>
            <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror"
                      placeholder="Décrivez votre article en détail : état, caractéristiques, raison de la vente…"
                      maxlength="5000">{{ old('description', $announcement?->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="text-muted">Minimum 20 caractères</small>
        </div>
    </div>
</div>

{{-- Category & City --}}
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3">Catégorie & Localisation</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Catégorie <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">-- Choisir --</option>
                    @php $grouped = $categories->groupBy('parent_id'); @endphp
                    @foreach($grouped->get(null, collect()) as $parent)
                        <optgroup label="{{ $parent->name }}">
                            @foreach($grouped->get($parent->id, collect()) as $child)
                                <option value="{{ $child->id }}"
                                    {{ old('category_id', $announcement?->category_id) == $child->id ? 'selected' : '' }}>
                                    {{ $child->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Ville <span class="text-danger">*</span></label>
                <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                    <option value="">-- Choisir --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}"
                            {{ old('city_id', $announcement?->city_id ?? auth()->user()->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

{{-- Price & Condition --}}
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3">Prix & État</h5>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-medium">Prix (MAD) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $announcement?->price) }}" min="0" step="0.01"
                           placeholder="0.00">
                    <span class="input-group-text">MAD</span>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">État <span class="text-danger">*</span></label>
                <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                    @foreach(['new' => 'Neuf', 'like_new' => 'Comme neuf', 'good' => 'Bon état', 'fair' => 'État correct', 'poor' => 'À rénover'] as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('condition', $announcement?->condition) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input type="checkbox" name="is_negotiable" id="is_negotiable" value="1"
                           class="form-check-input"
                           {{ old('is_negotiable', $announcement?->is_negotiable) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_negotiable">Prix négociable</label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Images --}}
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3">
            <i class="bi bi-images me-2 text-primary"></i>Photos
            <small class="text-muted fw-normal">(5 max, 4 Mo chacune)</small>
        </h5>

        @if($announcement && $announcement->images)
            <div class="d-flex flex-wrap gap-2 mb-3" id="currentImages">
                @foreach($announcement->images as $img)
                    <div class="position-relative">
                        <img src="{{ asset('storage/'.$img) }}" class="rounded" width="90" height="90" style="object-fit:cover">
                    </div>
                @endforeach
                <p class="w-100 small text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Sélectionner de nouvelles photos remplacera toutes les photos actuelles.
                </p>
            </div>
        @endif

        <input type="file" name="images[]" id="imagesInput" class="form-control @error('images') is-invalid @enderror"
               multiple accept="image/jpeg,image/png,image/webp">
        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

        {{-- Preview --}}
        <div class="d-flex flex-wrap gap-2 mt-3" id="imagePreview"></div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('imagesInput').addEventListener('change', function () {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    [...this.files].forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'rounded shadow-sm';
            img.style = 'width:90px;height:90px;object-fit:cover';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
