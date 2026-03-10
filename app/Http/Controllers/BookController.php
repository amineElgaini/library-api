<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        return Book::with('category')->get();
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)->with('category')->firstOrFail();
        $book->increment('views'); // increment views
        return $book;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:0',
            'damaged_copies' => 'nullable|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $book = Book::create($data);

        return response()->json($book, 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'author' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'nullable|string',
            'total_copies' => 'sometimes|required|integer|min:0',
            'damaged_copies' => 'sometimes|required|integer|min:0',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $book->update($data);

        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted']);
    }

    public function popular()
    {
        return Book::with('category')->orderBy('views', 'desc')->take(10)->get();
    }

    public function newArrivals()
    {
        return Book::with('category')->orderBy('created_at', 'desc')->take(10)->get();
    }
}