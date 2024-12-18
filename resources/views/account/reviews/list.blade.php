@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">

                @include('layouts.sidebar')

            </div>
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Reviews
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-end">
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" value="{{ Request::get('keyword') }}" id="search"
                                        name="keyword" class="form-control" placeholder="Search">
                                    <button class="btn btn-primary ms-1" type="submit">Searh</button>
                                    <a class="btn btn-warning ms-1 w-50" href="{{ route('review.list') }}">All Review</a>
                                </div>
                            </form>
                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Review</th>
                                    <th>Book</th>
                                    <th>Rating</th>
                                    <th>Created at</th>

                                    <th>Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td title="{{ $review->review }}">{{ $review->short_description }} <br>
                                                <strong>{{ $review->user->name }}</strong></td>
                                            <td>{{ $review->book->title }}</td>
                                            <td><i class="fa-regular fa-star"></i>{{ $review->rating }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}
                                            </td>

                                            <td>
                                                @if ($review->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('review.edit', $review->id) }}"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onClick="deleteReview({{$review->id}})" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash"></i></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-danger">
                                            No Reviews Avaliable
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                            </thead>
                        </table>
                        {{-- pagination  --}}
                        {{ $reviews->onEachSide(3)->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function deleteReview(id){
            if(confirm('Are you sure want to delete review')){
                $.ajax({
                url:'{{route("review.delete")}}',
                data: {
                    id:id
                },
                type: 'delete',
                headers: {
                        'X-CSRF-TOKEN':'{{csrf_token() }}'
                    },
                success: function(response){
                    window.location.href='{{route("review.list")}}'
                }

            });
            }
        }
    </script>
@endsection
