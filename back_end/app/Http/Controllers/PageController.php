<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Book;

class PageController extends Controller
{
    // CREATE operation
    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->input('title');
        $page->content = $request->input('content');
        $page->slug = $request->input('slug');
        $page->book_id = $request->input('book_id');

        $page->save();

        return response()->json($page);
    }

    // READ operation
    public function index()
    {
        $pages = Page::all();

        return response()->json($pages);
    }

    // UPDATE operation
    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        // Check if the authenticated user is the author of the book
        if ($page->book->user_id != auth()->id()) {
            return response()->json(['error' => 'You are not authorized to update this page.'], 403);
        }

        $page->title = $request->input('title');
        $page->content = $request->input('content');
        $page->slug = $request->input('slug');
        $page->book_id = $request->input('book_id');
        $page->save();

        return response()->json($page);
    }
    // DELETE operation
    public function destroy($id)
    {

        $page = Page::find($id);
        if ($page->book->user_id != auth()->id()) {
            return response()->json(['error' => 'You are not authorized to update this page.'], 403);
        }
        $page->delete();

        return response()->json(['message' => 'Page deleted']);
    }
    // public function deleteBookPages($id){
    //     $book = Book::find($id);
    //     if (!$book) {
    //         // Handle the case where the book with the given ID is not found
    //         return response()->json(['error' => 'Book not found'], 404);
    //     }

    //     $pages = Page::where('book_id', $id)->get();

    //     return response()->json(['message' => 'All Book Pages deleted']);

    // }

    public function GetAllBookPages($id)
    {
        $book = Book::find($id);

        if (!$book) {
            // Handle the case where the book with the given ID is not found
            return response()->json(['error' => 'Book not found'], 404);
        }

        $pages = Page::where('book_id', $id)->get();

        return response()->json($pages);
    }
    // GET operation
    public function show($id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json($page);
    }
}
