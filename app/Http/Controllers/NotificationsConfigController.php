<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\NotificationsConfig;
use JWTAuth;
use Illuminate\Support\Facades\Auth;


/**
 * @group Auth and User management
 *
 * APIs for managing users and their configuration
 */
class NotificationsConfigController extends Controller
{
    /**
     * Get the notification settings for current user
     *
     * Returns all notification settings of the current logged in User
     *
     * @authenticated
     *
     * @response {
     *    "nodeOffline": 0,
     *    "nodeOutdated": 0,
     *    "nodeStucked": 0,
     *    "weeklyMiningOutput": 0
     * }
     *
     *
     */
    public function showNotificationsConfig(){
        $user = JWTAuth::parseToken()->authenticate();
        $notificationsConfig = NotificationsConfig::where('user_id',$user->id)->first();
        if(!$notificationsConfig){
            $notificationsConfig = new NotificationsConfig([
                'user_id' => $user->id,
                'nodeOffline' => 0,
                'nodeOutdated' => 0,
                'nodeStucked'  => 0,
                'weeklyMiningOutput' => 0
            ]);
            $notificationsConfig->save();
        }
        return response()->json($notificationsConfig);
    }

    /**
     * Update notification settings
     *
     * Updates all notification settings of the current logged in User
     *
     * @authenticated
     *
     * @bodyParam  nodeOffline boolean required User will receive an eMail if one of his nodes go offline Example: 1
     * @bodyParam  nodeOutdated boolean required User will receive an eMail if one of his nodes is outdated Example: 1
     * @bodyParam  nodeStucked boolean required User will receive an eMail if one of his nodes is stucked Example: 1
     * @bodyParam  weeklyMiningOutput boolean required User will receive a weekly eMail with his mining stats Example: 1
     *
     * @response {
     *    "nodeOffline": 0,
     *    "nodeOutdated": 0,
     *    "nodeStucked": 0,
     *    "weeklyMiningOutput": 0
     * }
     *
     *
     *
     */
    public function updateNotificationsConfig(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $notificationsConfig = NotificationsConfig::where('user_id',$user->id)->first();
        $notificationsConfig->nodeOffline = $request->input('nodeOffline',false);
        $notificationsConfig->nodeOutdated = $request->input('nodeOutdated',false);
        $notificationsConfig->nodeStucked = $request->input('nodeStucked',false);
        $notificationsConfig->weeklyMiningOutput = $request->input('weeklyMiningOutput',false);
        $notificationsConfig->save();

        return response()->json($notificationsConfig);
    }
}
