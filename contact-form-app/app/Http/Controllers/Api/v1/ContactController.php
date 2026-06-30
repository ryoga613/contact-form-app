<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;


class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        return Response()->json(
            $contacts,
            200
        );
    }

    public function show(string $id)
    {
        $contact = Contact::Find($id);
        if(!$contact){
            return response()->json([
                'message'=>'お探しのお問い合わせは見つかりませんでした。'
            ],404);
        }
        return response()->json([
            'contact'=>$contact
            ]);
    }

    public function store(ContactRequest $request)
    {

        $validated = $request->validated();

        $contact = Contact::create($validated);
        return response()->json([
            'message' => 'お問合せが完了しました',
            'contacted_detail'=> $contact
        ]);
    }

    public function update(ContactRequest $request , string $id)
    {
        $contact = Contact::Find($id);

        if(!$contact){
            return response()->json([
                'message'=>'お探しのお問い合わせは見つかりませんでした。'
            ],404);
        }

        $validated = $request->validated();

        $contact->update($validated);

        return response()->json([
                'message'=>'お問合せ情報を更新しました',
                'contact'=> $contact
            ],200);
    }

    public function destroy(string $id)
    {
        $contact = Contact::Find($id);

        if(!$contact){
            return response()->json([
                'message'=>'お探しのお問い合わせは見つかりませんでした。'
            ],404);
        }
        $contact->delete();
        return response()->json([
            'message' => 'お問合せが削除されました。',
        ]);
    }
}
