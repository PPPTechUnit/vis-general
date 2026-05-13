<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Models\Notification;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use \Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;
use \Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(1000);
        return view('backend.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('backend.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Notification::create([
            'message' => $request->message,
        ]);

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        return view('backend.notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $notification->update([
            'message' => $request->message,
        ]);

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    public function verify($id)
    {
        $data = Notification::find($id);
        $data->verified_by = isset(Auth::user()->id) ? Auth::user()->id : "";
        date_default_timezone_set("Asia/Karachi");
        $data->verified_at = date('Y-m-d h:i:s', time());
        $data->sent = 1;
        $result = $data->save();
        $notification = Notification::where('id', $id)->first()->toArray();
        $this->sendFirebaseNotification($notification);

        if ($result == 1) {
            return redirect()->back()->with('success', 'Notification sent Successfully.');
        } else {
            return redirect()->back()->with('error', '!Oops something went wrong please try again.');
        }
    }

    public function sendFirebaseNotification($para)
    {
        $file_path = storage_path('/jiyala-notification.json');

        $factory = (new Factory)->withServiceAccount($file_path);
        $messaging = $factory->createMessaging();

        $members_count = DB::table("app_web_users")
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->count();

        \Log::info('FCM: Total users with token: ' . $members_count);

        $pages = ceil($members_count / 1000);

        for ($a = 0; $a < $pages; $a++) {
            $notification_members = [];
            $insertData = [];
            $skip = $a * 1000;

            $members = DB::table("app_web_users")
                ->whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->skip($skip)
                ->take(1000)
                ->get();

            foreach ($members as $member) {
                $notification_members[] = $member->fcm_token;
                $insertData[] = [
                    'user_id'         => $member->id,
                    'notification_id' => $para['id'],
                    'created_at'      => now(),
                ];
            }

            DB::table('notifications_users')->insert($insertData);

            \Log::info('FCM: Sending batch ' . ($a + 1) . ' to ' . count($notification_members) . '
  tokens');
            \Log::info('FCM: Tokens in this batch: ' . json_encode($notification_members));

            $plainTextBody_1 = strip_tags($para['message']);

            $message = CloudMessage::new()
                ->withNotification(FirebaseNotification::create('VIS - Notification', $plainTextBody_1))
                ->withData(['title' => 'VIS - Notification', 'body' => $plainTextBody_1])
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority' => 'high',
                    'notification' => [
                        'channel_id' => 'vis_high_importance_channel',
                    ],
                ]))
                ->withApnsConfig(ApnsConfig::fromArray([
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => [
                        'alert' => ['title' => 'VIS - Notification', 'body' => $plainTextBody_1],
                        'sound' => 'default',
                    ]],
                ]));

            try {
                $response = $messaging->sendMulticast($message, $notification_members);

                $successCount = $response->successes()->count();
                $failureCount = $response->failures()->count();

                \Log::info('FCM: Successes: ' . $successCount . ', Failures: ' . $failureCount);
                echo 'Batch ' . ($a + 1) . ' — Success: ' . $successCount . ', Failures: ' .
                    $failureCount . '<br>';

                foreach ($response->failures()->getItems() as $failure) {
                    $failedToken = $failure->target()->value();
                    $errorMsg    = $failure->error()->getMessage();
                    \Log::error('FCM failure — token: ' . $failedToken . ' | error: ' . $errorMsg);
                    echo 'Failed token: ' . $failedToken . ' | Error: ' . $errorMsg . '<br>';
                }

            } catch (\Throwable $e) {
                \Log::error('FCM exception: ' . $e->getMessage());
                echo 'Error: ' . $e->getMessage();
            }
        }
    }
}