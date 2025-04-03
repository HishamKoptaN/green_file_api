<?php

namespace App\Http\Controllers;

use App\Services\FirestoreService;
use Illuminate\Http\Request;

class FirestoreController extends Controller
{
    protected $firestore;

    public function __construct(FirestoreService $firestore)
    {
        $this->firestore = $firestore;
    }
    // 🔹 جلب البيانات
    public function index()
    {
        $documents = $this->firestore->getDocuments('users');
        return response()->json($documents);
    }
    // 🔹 إضافة مستخدم جديد
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
        $this->firestore->createDocument('users', $data);
        return response()->json(['message' => 'User added successfully']);
    }
    // 🔹 تحديث بيانات المستخدم
    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
        $this->firestore->updateDocument('users', $id, $data);
        return response()->json(['message' => 'User updated successfully']);
    }

    // 🔹 حذف مستخدم
    public function destroy($id)
    {
        $this->firestore->deleteDocument('users', $id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
