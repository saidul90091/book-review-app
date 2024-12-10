<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // this method will show books listing page
    public function index(Request $request)
    {

        $books = Book::orderBy('created_at', 'DESC');

        if(!empty($request->keyword)){
            $books->where('title','like','%'.$request->keyword.'%');
        }

        $books = $books->paginate(3);

        return view('books.list',[
            'books' => $books
        ]);

        // *****
        // $books = Book::orderBy('created_at', 'DESC')->paginate(3);
        // return view('books.list',[
        //     'books' => $books
        // ]);

        // *****
        // $books = Book::paginate(3);
        // return view('books.list', compact('books'));
    }



    // this method will create book page
    public function create()
    {
        return view('books.create');
    }

    // this method will store book in database
    public function store(Request $request)
    {

        $ruls = [
            'title' => 'required|min:3',
            'author' => 'required|min:3',
            'status' => 'required'
        ];

        if (!empty($request->image)) {
            $ruls['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $ruls);

        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }
        // book store in DB
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // book Image upload
        if (!empty($request->image)) {
            File::delete(public_path('uploads/books/'. $book->image)); //delete previous image

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);

            $book->image = $imageName;
            $book->save();
        }
        return redirect()->route('books.index')->with('success', 'Book added successfully');
    }

    // this method will show edit book page
    public function edit($id) {
        $book = Book::findOrFail($id);
        return view('books.edit',[
            'book' => $book
        ]);
    }

    // this method will update a book
    public function update($id, Request $request) {
        $book = Book::findOrFail($id);
        $ruls = [
            'title' => 'required|min:3',
            'author' => 'required|min:3',
            'status' => 'required'
        ];

        if (!empty($request->image)) {
            $ruls['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $ruls);

        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }
        // book update in DB

        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // book Image upload
        if (!empty($request->image)) {
            File::delete(public_path('uploads/books/'.$book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);

            $book->image = $imageName;
            $book->save();
        }
        return redirect()->route('books.index')->with('success', 'Book updated  successfully');
    }


    // this method  will delete a book from database
    public function destroy(Request $request) {
        $book = Book::find($request->id);
        if($book == null){
            session()->flash('error', 'book not found');
            return response()->json([
                'status' => false,
                'message' => 'book not found'
            ]);
        }else{
            File::delete(public_path('uploads/books/'.$book->image));
            $book->delete();

            session()->flash('success', 'book deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'book deleted successfully'
            ]);
        }
    }
}
