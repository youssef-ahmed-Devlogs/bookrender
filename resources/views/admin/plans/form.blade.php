<div>
    <!-- Plan Name -->
    <div class="mb-3 w-75">
        <label for="name" class="form-label">Plan Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
            placeholder="Enter Plan Name" value="{{ old('name', $plan?->name) }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Description -->
    <div class="mb-3 w-75">
        <label for="description" class="form-label">Description</label>

        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
            placeholder="Enter Description" rows="3">{{ old('description', $plan?->description) }}</textarea>

        <p class="font-main">Set the subscription duration. Leave 0 for unlimited.</p>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Price -->
    <div class="mb-3 w-75">
        <label for="price" class="form-label">Price</label>

        <div class="input-group w-100">
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price"
                placeholder="Enter Price" value="{{ old('price', $plan?->price) }}">
            <span class="input-group-text">USD</span>
        </div>

        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Book Number -->
    <div class="mb-3 w-75">
        <label for="book" class="form-label">Book Number</label>
        <input type="number" name="book" class="form-control @error('book') is-invalid @enderror" id="book"
            placeholder="Enter Book Number" value="{{ $plan?->book_number }}">

        @error('book')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Word Number -->
    <div class="mb-3 w-75">
        <label for="word" class="form-label">Word Number</label>
        <input type="number" name="word" class="form-control @error('word') is-invalid @enderror" id="word"
            placeholder="Enter Word Number" value="{{ $plan?->word_number }}">

        <p class="w-100 font-main">Amount you want to charger people who join this plan.
            Leave 0 if you want this plan to be free.</p>

        @error('word')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Free Status -->
    <div class="mb-3 w-75">
        <label for="is_free" class="form-label">Free Status</label>

        <select name="is_free" class="form-select" id="is_free">
            @if (!$hasFreePlan)
                <option value="1" selected>free</option>
            @endif

            <option value="0">notfree</option>
        </select>

        @error('is_free')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status -->
    <div class="mb-3 w-75">
        <label for="status" class="form-label">Status</label>

        <select name="status" class="form-select" id="status">
            <option value="active" @selected($plan?->status === 'active')>Active</option>
            <option value="disactive" @selected($plan?->status === 'disactive')>Inactive</option>
        </select>

        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>