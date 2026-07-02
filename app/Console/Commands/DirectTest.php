<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DirectTest extends Command
{
    protected $signature = 'app:direct-test';
    protected $description = 'Smoke test director pipe';

    public function handle()
    {
        $this->info('halo');
        $this->line('halo');
        $this->error('halo');

        $body = [
            "model" => "qwen3.5:9b",
            "messages"=>[[
                "role"=>"user",
                "content"=>"Reply only with a JSON object that has a single key ok set to true."
            ]],
            "stream"=>false,
            "think"=>false,
            "format"=>[
                "type"=>"object",
                "properties"=>[
                    "ok"=>[
                        "type"=>"boolean"
                    ]
                ],
                "required"=>["ok"]
            ],
            "stream"=>false,
            "think"=>false
        ];

        $response = Http::timeout(120)->post('http://127.0.0.1:11434/api/chat',$body);
        $content = $response->json('message.content');

        $clean = trim(Str::after($content,'</think>'));  # only if think true or using model 4b where think cant be disabled
        print_r(json_decode($clean, true));
        print_r($content);
        return Command::SUCCESS;
    }
}
