@csrf
@if($business->exists) @method('put') @endif
<div class="form-grid">
    <label>Business name <input name="name" value="{{ old('name', $business->name) }}" required></label>
    <label>Category
        <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $business->category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Contact number <input name="contact_number" value="{{ old('contact_number', $business->contact_number) }}" required></label>
    <label>Email <input type="email" name="email" value="{{ old('email', $business->email) }}"></label>
    <label>City <input name="city" value="{{ old('city', $business->city) }}"></label>
    <label>Address <input name="address" value="{{ old('address', $business->address) }}" required></label>
</div>
<label>Description <textarea name="description" required>{{ old('description', $business->description) }}</textarea></label>

<section class="panel stack">
    <h2>Services</h2>
    @for($i = 0; $i < 4; $i++)
        @php($service = $business->services[$i] ?? null)
        <div class="form-grid">
            <label>Service name <input name="services[{{ $i }}][name]" value="{{ old("services.$i.name", $service->name ?? '') }}"></label>
            <label>Service description <input name="services[{{ $i }}][description]" value="{{ old("services.$i.description", $service->description ?? '') }}"></label>
        </div>
    @endfor
</section>

<section class="panel stack">
    <h2>Business Hours</h2>
    @foreach($days as $day)
        @php($hour = $business->hours->firstWhere('day', $day))
        <div class="form-grid">
            <label>{{ $day }} opens <input type="time" name="hours[{{ $day }}][opens_at]" value="{{ old("hours.$day.opens_at", $hour->opens_at ?? '09:00') }}"></label>
            <label>{{ $day }} closes <input type="time" name="hours[{{ $day }}][closes_at]" value="{{ old("hours.$day.closes_at", $hour->closes_at ?? '17:00') }}"></label>
            <label style="display:flex;gap:8px;align-items:center;font-weight:400"><input style="width:auto" type="checkbox" name="hours[{{ $day }}][is_closed]" value="1" @checked(old("hours.$day.is_closed", $hour->is_closed ?? false))> Closed</label>
        </div>
    @endforeach
</section>

<section class="panel stack">
    <h2>Photos</h2>
    @if($business->photos->isNotEmpty())
        <div class="photo-grid">
            @foreach($business->photos as $photo)<img src="{{ asset('storage/'.$photo->path) }}" alt="{{ $business->name }}">@endforeach
        </div>
    @endif
    <label>Upload photos <input type="file" name="photos[]" multiple accept="image/*"></label>
</section>

<div class="actions">
    <button>{{ $business->exists ? 'Update Business' : 'Submit Business' }}</button>
    <a class="button secondary" href="{{ route('owner.businesses.index') }}">Cancel</a>
</div>
