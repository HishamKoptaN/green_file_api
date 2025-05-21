<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Post\PollVote;
use App\Models\Social\Post\PollOption;

class PollsApiController extends Controller
{
    public function vote(Request $request)
    {
        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);
        $user = Auth::user();
        $newOption = PollOption::findOrFail($request->poll_option_id);
        // البحث عن تصويت المستخدم السابق لنفس التصويت
        $existingVote = PollVote::where('user_id', $user->id)
            ->whereIn('poll_option_id', PollOption::where('poll_id', $newOption->poll_id)->pluck('id'))
            ->first();

        if ($existingVote) {
            // استرجاع الخيار القديم
            $oldOption = PollOption::find($existingVote->poll_option_id);
            // تعديل عدد الأصوات
            $oldOption->decrement('votes');
            $newOption->increment('votes');
            // تحديث التصويت إلى الخيار الجديد
            $existingVote->update([
                'poll_option_id' => $newOption->id,
            ]);
        } else {
            // زيادة التصويت للخيار الجديد
            $newOption->increment('votes');
            // إنشاء تصويت جديد
            PollVote::create(
                [
                    'user_id' => $user->id,
                    'poll_option_id' => $newOption->id,
                ],
            );
        }
        return successRes(null, 200);
    }
}
