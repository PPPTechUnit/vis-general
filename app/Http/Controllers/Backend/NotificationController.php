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
use function Symfony\Component\Cache\Traits\object; // <-- alias this

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications.
     */
    public function index()
    {
        $notifications = Notification::latest()->paginate(1000);
        return view('backend.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification.
     */
    public function create()
    {
        return view('backend.notifications.create');
    }

    /**
     * Store a newly created notification in storage.
     */
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

    /**
     * Show the form for editing the specified notification.
     */
    public function edit(Notification $notification)
    {
        return view('backend.notifications.edit', compact('notification'));
    }

    /**
     * Update the specified notification in storage.
     */
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

    /**
     * Remove the specified notification from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    public function verify ($id)
    {
        $data = Notification::find($id);
        $data->verified_by =  isset(Auth::user()->id)?Auth::user()->id:"";
        date_default_timezone_set("Asia/Karachi");
        $data->verified_at =  date('Y-m-d h:i:s', time());
        $data->sent = 1;
        $result = $data->save();
        $notification = Notification::where('id',$id)->first()->toArray();
        $this->sendFirebaseNotification($notification);

        if($result == 1) {
            return redirect()->back()->with('success', 'Notification sent Successfully.');
        } else {
            return redirect()->back()->with('error', '!Oops something went wrong please try again.');
        }
    }

    public function sendFirebaseNotification($para)
    {
        $file_path = storage_path('/vis-gb-notification.json');
        $sa = json_decode(file_get_contents($file_path), true);
        \Log::info('SA email: ' . ($sa['client_email'] ?? 'MISSING') . ' | project: ' . ($sa['project_id'] ??'MISSING'));
        // Initialize Firebase with the service account JSON file
        $factory = (new Factory)->withServiceAccount($file_path);
        // Get the Messaging instance
        $messaging = $factory->createMessaging();
        $members_count = DB::table("app_web_users")
            ->where('kill_switch','0')
            ->whereNotNull('fcm_token')->count();
        $pages = ceil($members_count / 1000);
        for ($a = 0; $a < $pages; $a++) {
            $notification_members = [];
            $insertData = [];
            $skip = $a * 1000;
            $members = DB::table("app_web_users")->whereNotNull('fcm_token')
                ->where('kill_switch','0')
                ->skip($skip)->take(1000)->get();


            foreach ($members as $member) {
                $notification_members[] = $member->fcm_token;
                $insertData[] = [
                    'user_id'         => $member->id,
                    'notification_id' => $para['id'],
                    'created_at'      => now(),
                ];
            }

            DB::table('notifications_users')->insert($insertData);


            $title = 'VIS - Notification';
            $plainTextBody_1 = trim(strip_tags($para['message']));

            $message = CloudMessage::new()
                ->withNotification(FirebaseNotification::create($title, $plainTextBody_1))
                ->withData([
                    'title' => $title,
                    'body' => $plainTextBody_1,
                    'message' => $plainTextBody_1,
                    'notification_id' => (string) $para['id'],
                ])
                ->withApnsConfig(ApnsConfig::fromArray([
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body' => $plainTextBody_1,
                            ],
                            'sound' => 'default',
                        ],
                    ],
                ]));

            //echo "<pre>"; print_r($notification_members);

            // Send the message to the devices
            try {
                //$start = microtime(true);
                $response = $messaging->sendMulticast($message, $notification_members);
                // $end = microtime(true);
                // $duration = $end - $start;
                // echo "Notification sent in $duration seconds<br>";
                \Log::info('FCM Successes: ' . $response->successes()->count() . ' / Failures: ' .
                    $response->failures()->count());
                foreach ($response->failures()->getItems() as $failure) {
                    \Log::error('FCM FAIL: ' . $failure->target()->value() . ' — ' . $failure->error()->getMessage());
                }


                echo 'Success: ' . $response->successes()->count() . ' messages were sent successfully.';
                //  echo 'Success: ' . $response->successes()->count() . ' messages were sent successfully.';
                \Log::info($plainTextBody_1);
                \Log::info($notification_members);

            } catch (\Throwable $e) {
                echo 'Error: ' . $e->getMessage();
                echo 'Error: ' . $e->getMessage();
                \Log::error($e->getMessage());
            }
        }
    }


}