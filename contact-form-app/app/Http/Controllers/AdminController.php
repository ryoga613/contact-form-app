<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('keyword')) {
            $searchKeyword = $request->input('keyword');

            $query->where(
                'first_name',
                'like',
                '%'.$searchKeyword.'%'
            )
                ->orWhere(
                    'last_name',
                    'like',
                    '%'.$searchKeyword.'%'
                )
                ->orwhere(
                    'email',
                    'like',
                    '%'.$searchKeyword.'%'
                );
        }

        if ($request->filled('gender')) {
            $searchGender = $request->input('gender');
            $query->where(
                'gender', $searchGender
            );
        }

        if ($request->filled('category_id')) {
            $searchCategory_id = $request->input('category_id');
            $query->where('category_id', $searchCategory_id);

        }

        if ($request->filled('date') && $request->has('date')) {
            $searchDate = $request->input('date');
            $query->whereDate(
                'created_at', $searchDate
            );
        }

        $categories = Category::all();
        $contacts = $query->paginate(7);
        $tags = Tag::all();

        return view('admin.index', compact('categories', 'contacts', 'tags'));
    }

    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);

        return view('admin.show', compact('contact'));
    }

    public function destroy(string $id)
    {
        Contact::destroy($id);

        return redirect('/admin');
    }
}
