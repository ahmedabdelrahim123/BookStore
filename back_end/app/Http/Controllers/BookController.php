<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // CREATE operation
    public function store(Request $request)
    {
       if (auth()){
        $book = new Book;
        $book->title = $request->input('title');
        $book->description = $request->input('description');
        $book->user_id = auth()->id();;
        $book->save();

        return response()->json($book);
    }
        return response()->json("please login first");
    }

    // READ operation
    public function index()
    {
        $books = Book::all();

        return response()->json($books);
    }

    // GET operation
    public function show($id)
    {
    $book = Book::find($id);

    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    return response()->json($book);
    }

    // UPDATE operation
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        // if ($book->user_id != auth()->id()) {
        //     return response()->json(['error' => 'You are not authorized to update this page.'], 403);
        // }
        $book->title = $request->input('title');
        $book->description = $request->input('description');
        // $book->user_id = $request->input('user_id');
        $book->save();

        return response()->json($book);
    }

    // DELETE operation
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found.'], 404);
        }

        // if ($book->user_id != auth()->id()) {
        //     return response()->json(['error' => 'You are not authorized to delete this book.'], 403);
        // }

        // Delete related pages
        $book->page()->delete();

        // Delete the book
        $book->delete();

        return response()->json(['message' => 'Book and related pages deleted']);
    }

}
