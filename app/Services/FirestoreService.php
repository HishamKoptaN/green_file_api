<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirestoreService
{
    protected $projectId;
    protected $baseUrl;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    // 🔹 إضافة إشعار إلى Firestore
    public function storeNotification($data)
    {
        $response = Http::post("{$this->baseUrl}/notifications", ['fields' => $this->formatData($data)]);
        return $response->json();
    }

    // 🔹 تحويل بيانات Laravel إلى صيغة Firestore
    private function formatData($data)
    {
        $formatted = [];
        foreach ($data as $key => $value) {
            $formatted[$key] = ['stringValue' => $value];
        }
        return $formatted;
    }
}
