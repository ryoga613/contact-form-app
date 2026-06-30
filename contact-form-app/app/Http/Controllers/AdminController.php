<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse; 

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

    public function export(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('first_name', 'like', "%{$keyword}%")
                  ->orWhere('last_name', 'like', "%{$keyword}%");
        }

        $contacts = $query->get();

        $csvHeader = ['ID', '名前', 'メールアドレス', 'お問い合わせ内容', '登録日時'];

        $response = new StreamedResponse(function () use ($csvHeader, $contacts) {
            
            $stream = fopen('php://output', 'w');
            
            fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($stream, $csvHeader);

            foreach ($contacts as $contact) {
                $row = [
                    $contact->id,
                    $contact->first_name . ' ' . $contact->last_name,
                    $contact->email,
                    $contact->detail,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ];

                fputcsv($stream, $row);
            }

            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv');
        
        $filename = 'contacts_' . date('Ymd') . '.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        return $response;
    }
    
}
