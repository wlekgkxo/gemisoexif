<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CelebrityController extends Controller
{
    public function getWho(Request $request) {
        // $image = $request->file('celebrity_image');

        $image_url = $request->celebrity_image_url;

        // dd($image_url);

        $PAT = env('CLARIFAI_API_KEY');
        // Specify the correct user_id/app_id pairings
        // Since you're making inferences outside your app's scope
        $USER_ID = "clarifai";
        $APP_ID = "main";
        // Change these to whatever model and image URL you want to use
        $MODEL_ID = "celebrity-face-detection";
        $MODEL_VERSION_ID = "0676ebddd5d6413ebdaa101570295a39";
        // $IMAGE_URL = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTe5U0-d4nptwhHou3dI84naxVKuHgPbIlgRXzIBRugPq9zqAQw";

        $client = new Client();
        $url = 'https://api.clarifai.com/v2/models/celebrity-face-recognition/outputs';

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Key '.$PAT,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'user_app_id' => [
                        "user_id" => $USER_ID,
                        "app_id" => $APP_ID,
                    ],
                    'model_id' => $MODEL_ID,
                    'version_id' => $MODEL_VERSION_ID, // This is optional. Defaults to the latest model version
                    'inputs' => [
                        [
                            'data' => [
                                'image' => [
                                    'url' => $image_url
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            $tags = [];
            if (isset($data['outputs'][0]['data']['concepts'])) {
                foreach ($data['outputs'][0]['data']['concepts'] as $concept) {

                    $tags[] = [
                        'celebrity' => $concept['name'],
                        'confidence' => $concept['value'] * 100,
                    ];
                }
            }

            return $tags;
        } catch(Exception $e) {
            dd($e->getMessage());
        }


    }
}
