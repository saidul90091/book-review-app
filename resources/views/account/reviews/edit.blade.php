@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">

                @include('layouts.sidebar')

            </div>
            <div class="col-md-9">

                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Edit Reviews
                    </div>

                    <div class="card-body">
                        <form action="{{ route('review.update', $review->id) }}" method="post">
                            @csrf
                        
                            <div class="mb-3">
                                <label for="name" class="form-label">Review</label>
                                <textarea name="review" id="review" class="@error('review') is-invalid

                                @enderror form-control" cols="30" rows="3">{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid

                                @enderror">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Block</option>
                                </select>

                                @error('status')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
