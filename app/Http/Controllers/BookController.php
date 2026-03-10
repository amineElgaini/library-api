<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('views', 'desc');
                    break;
                case 'new':
                    $query->where('views', 0)->orderBy('created_at', 'desc');
                    break;
            }
        }

        $books = $query->paginate(10);

        return response()->json($books);
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)->with('category')->firstOrFail();
        $book->increment('views');
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

        $title = $data['title'];
        $slug = Str::slug($title);
        $count = Book::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $data['slug'] = $slug;

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
            $title = $data['title'];
            $slug = Str::slug($title);
            $count = Book::where('slug', 'LIKE', "{$slug}%")->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $data['slug'] = $slug;
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
}
