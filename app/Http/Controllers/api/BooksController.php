<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\BooksStoreRequest;
use Illuminate\Http\Request;
use App\Models\Book;

class BooksController extends Controller
{
    public function index(Book $book)
    {
        return response()->json($book::all());
    }

    public function show($id)
    {
        $book = Book::find($id);

        return response()->json($book);
    }

    public function store(BooksStoreRequest $request)
    {
        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    public function update($id, Request $request)
    {
        $book = Book::find($id);

        $book->update( $request->all());

        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        $book->delete();

        return response()->json([], 204);
    }
}
