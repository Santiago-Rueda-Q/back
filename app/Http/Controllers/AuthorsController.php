<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     *  Display a listing of the resource.
     *  @return \Illuminate\Http\Response
     *  @throws \Exception
     *  @OA\Get(
     *      path="/api/authors",
     *      tags={"Authors"},
     *      summary="Get all authors",
     *      @OA\Response(
     *          response=200,
     *          description="Returns a list of authors",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Author")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error retrieving authors"
     *      )
     *  )
     */
    public function index()
    {

    try {
        $Author = Author::all();
        return response()->json($Author);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al obtener los Author', 'message' => $e->getMessage()], 500);
    }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     *  Store a newly created resource in storage.
     *  @param \Illuminate\Http\Request $request
     *  @return \Illuminate\Http\Response
     *  @throws \Illuminate\Validation\ValidationException
     *  @OA\Post(
     *      path="/api/authors",
     *      tags={"Authors"},
     *      summary="Create a new author",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"nombre", "email"},
     *              @OA\Property(property="nombre", type="string", example="John Doe"),
     *
     */
    public function store(Request $request)
    {

        $author = new Author();
        $author->nombre = $request->input('nombre');
        $author->email = $request->input('email');
        $author->biografia = $request->input('biografia');
        $author->save();

        return response()->json(['message' => 'Autor creado correctamente', 'author' => $author], 201);

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    $author = Author::findOrFail($id);
    return response()->json($author);

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
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255' . $id,
        'biografia' => 'nullable|string',
    ]);

    $author = Author::findOrFail($id);
    $author->update($validated);

    return response()->json($author);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    try {
        $author = Author::findOrFail($id);
        $author->delete();
        return response()->json(['mensaje' => 'Author eliminado']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Author no encontrado', 'message' => $e->getMessage()], 404);
    }

    }
}
