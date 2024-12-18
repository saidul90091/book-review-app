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
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Add Book</a>
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" value="{{Request::get('keyword')}}" id="search" name="keyword" class="form-control"
                                        placeholder="Search">
                                    <button class="btn btn-primary ms-1" type="submit">Searh</button>
                                    <a class="btn btn-warning ms-1 w-50" href="{{ route('books.index') }}">All Books</a>
                                </div>
                            </form>
                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Author</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            <tbody>
                                @if ($books->isNotempty())
                                    @foreach ($books as $book)
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td><img width="50px;" height="50px" src="{{ asset('/uploads/books/' . $book->image) }}"
                                                    alt=""></td>
                                            <td>{{ $book->author }}</td>
                                            {{-- <td>{{ Str::limit($book->description, 30, '...') }}</td> --}}
                                            <td class="" title="{{$book->description}}">{{ $book->short_description }}</td>
                                            <td>
                                                @if ($book->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-star"></i></a>
                                                <a href="{{ route('books.edit', $book->id) }}"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onclick="deleteBook({{ $book->id }})"
                                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr colspan="6">
                                        <td>Books not found</td>
                                    </tr>
                                @endif
                            </tbody>

                            </thead>
                        </table>

                        @if ($books->isNotEmpty())
                            {{ $books->onEachSide(5)->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function deleteBook(id) {
            if (confirm("Are you sure want to delete")) {
                $.ajax({
                    url: '{{ route("books.destroy") }}',
                    type: 'delete',
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN':'{{csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '{{ route("books.index") }}';
                    }
                });
            }
        }
    </script>
@endsection
