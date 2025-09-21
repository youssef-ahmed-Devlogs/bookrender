<div>
    <div class="mb-3 w-75">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image"
            value="{{ old('image', $rating?->image) }}">

        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>