<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Book::with('autor')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    /*$request->validate([
        'titulo' => 'required|string|max:255',
        'sinopsis' => 'nullable|string',
        'autor_id' => 'required|exists:autores,id',
    ]);*/

    $book = new Book();
    $book->titulo = $request->input('titulo');
    $book->sinopsis = $request->input('sinopsis');
    $book->autor_id = $request->input('autor_id');
    $book->save();

    //$book = Book::create($request->all());

    return response()->json(['message' => 'Libro creado correctamente', 'book' => $book], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Book::with('autor')->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'autor_id' => 'required|exists:autores,id',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        return response()->json($book);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    
        try {
        $Books = Book::findOrFail($id);
        $Books->delete();
        return response()->json(['mensaje' => 'Book eliminado']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Book no encontrado', 'message' => $e->getMessage()], 404);
    }
    }
}
