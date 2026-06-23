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
            "model" => "qwen3:4b",
            "messages"=>[[
                "role"=>"user",
                "content"=>"Sebutkan 3 buah. /no_think"
            ]],
            "stream"=>false,
            "think"=>false,
            "format"=>[
                "type"=>"object",
                "properties"=>[
                    "buah"=>[
                        "type"=>"array",
                        "items"=>["type"=>"string"]
                    ]
                ],
                "required"=>["buah"]
            ]
        ];

        $response = Http::timeout(120)->post('http://127.0.0.1:11434/api/chat',$body);
        $content = $response->json('message.content');

        $clean = trim(Str::after($content,'</think>'));
        print_r(json_decode($clean, true));

        return Command::SUCCESS;
    }
}
