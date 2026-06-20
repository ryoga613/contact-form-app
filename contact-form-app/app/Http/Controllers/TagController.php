<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);

        return view('admin.tags.edit', compact('tag'));
    }

    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        Tag::create($validated);

        return redirect('/admin');
    }

    public function update(StoreTagRequest $request, string $id)
    {
        $validated = $request->validated();
        $tag = Tag::findOrFail($id);
        $tag->update($validated);

        return redirect('/admin');

    }

    public function destroy(string $id)
    {
        Tag::destroy($id);

        return redirect('/admin');
    }
}
