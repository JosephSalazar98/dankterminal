<?php

namespace App\Controllers;

use App\Models\Meme;
use GuzzleHttp\Client;
use App\Models\Caption;
use Illuminate\Pagination\LengthAwarePaginator;

class EmbedsController extends Controller
{
    public function loadFromArray()
    {
        $memes = [
            [
                'title' => '122',
                'description' => 'Hello Kitty with an emotionless expression is edited to hold a hyper-realistic revolver using a human hand, aimed directly at the viewer. The contrast between innocence and aggression creates a surreal, threatening vibe. The character is standing in a warm indoor setting, adding to the visual dissonance. It evokes memes about unexpected rage, passive-aggressive moods, or absurdly overreacting to minor inconveniences.',
                'image_path' => '/assets/img/122.png',
                'category' => 'animales'
            ],
            [
                'title' => '134',
                'description' => 'A crudely drawn humanoid cartoon character with a blank, emotionless face is severely bruised, battered, and covered in cuts. Its eyes are swollen and one is blackened, yet it maintains a stoic, resigned expression. The exaggerated injuries contrast with the character’s passive demeanor, creating a mood of silent suffering or emotional numbness. Commonly used in memes to reflect internal exhaustion, emotional damage, or silently enduring life’s absurd beatings.',
                'image_path' => '/assets/img/134.png',
                'category' => 'surreal'
            ],
            [
                'title' => '137',
                'description' => 'An ordinary bathroom setting featuring an extravagant, highly reflective toilet designed entirely out of diamond-like crystal. The toilet’s bowl, tank, and lid sparkle with faceted cuts resembling a massive gemstone, sitting on a spotlighted pedestal. The lavish appearance contrasts absurdly with its mundane function, making it perfect for memes about unnecessary luxury, misplaced priorities, or treating everyday experiences like royalty.',
                'image_path' => '/assets/img/137.png',
                'category' => 'clásico'
            ],
            [
                'title' => '8',
                'description' => 'A close-up image of a dog with a startled or alarmed expression. Its wide-open eyes are looking sharply to the side, giving off a strong sense of surprise or suspicion. The dog has a black and white coat, with a prominent white stripe running down its head. The background appears to be an indoor wooden setting, slightly blurred, which keeps the focus entirely on the dogs intense gaze . The overall composition captures a sudden moment of realization or silent panic,
                commonly used in reaction memes.',
                'image_path' => '/assets/img/8.png',
                'category' => 'clásico'
            ],
            [
                'title' => '23',
                'description' => 'An absurd and humorous image of a goose standing on a dirt path with grass around it. The goose’s body has been edited to have two human arms emerging from its sides, striking exaggerated gang signs with its fingers. The goose’s posture, combined with the unnatural limbs and its intense expression, creates a surreal, chaotic, and ridiculous vibe. The unnatural combination of species and gestures makes it ideal for meme themes involving fake bravado, overconfidence, or ironic displays of dominance.',
                'image_path' => '/assets/img/23.png',
                'category' => 'clásico'
            ],
            [
                'title' => '24',
                'description' => 'A digitally generated or heavily edited portrait of a man with an awkward, forced smile and an unnatural, almost surreal look. His beard and hair appear overly smooth or artificial, and the blending around the neck area looks off, suggesting possible AI generation or image manipulation. The background is a grid of soft pastel and gray squares, reminiscent of tech or startup aesthetics. The overall expression and uncanny presentation make it ideal for memes about pretending everything is fine, fake confidence, or awkward social moments.',
                'image_path' => '/assets/img/24.png',
                'category' => 'clásico'
            ],
            [
                'title' => '34',
                'description' => 'A crudely drawn, low-resolution hybrid character combining Pikachu and Homer Simpson. The figure has Pikachu’s yellow body, ears, and tail, but with Homer’s iconic bug-eyed stare and overbite lips. The background is a colorful space gradient filled with stars, adding an ironic sense of cosmic grandeur to the otherwise ridiculous figure. The absurdity, poor drawing quality, and chaotic crossover make it perfect for surreal or nonsensical memes — especially those leaning into irony, low-effort humor, or distorted nostalgia.',
                'image_path' => '/assets/img/34.png',
                'category' => 'clásico'
            ],
            [
                'title' => '36',
                'description' => 'A tightly cropped image showing someone whispering into another person’s ear. The person receiving the whisper is slightly smiling, with a trimmed beard and mustache, while the whisperers mouth is close to their ear. The background is pure white, giving it a sterile, meme-template vibe. This setup is widely used for memes that involve revealing niche, cursed, or unnecessary knowledge — often captioned with something absurd, awkward, or conspiratorial being whispered and a smug or ironic reaction.',
                'image_path' => '/assets/img/36.png',
                'category' => 'clásico'
            ],
            [
                'title' => '74',
                'description' => 'A low-resolution 3D render of Sonic the Hedgehog giving double thumbs-up with a smug, sarcastic expression. His eyes are half-closed, mouth slightly open in a cocky grin, and his whole posture screams ironic approval. The background features a grassy area and a stone wall, but it’s clearly secondary to the character’s exaggerated attitude. This image is a classic format for sarcastically endorsing something obviously bad, cringe, or cursed — perfect for memes dripping with irony or mocking fake confidence.',
                'image_path' => '/assets/img/74.png',
                'category' => 'clásico'
            ],
            [
                'title' => '95',
                'description' => 'A still from *Star Trek: The Next Generation* showing two crew members in yellow uniforms, Data and Geordi La Forge, intently examining a large futuristic monitor. On the screen is humorously displayed the unmistakable icon for a car’s “check engine” light — a symbol entirely out of place in a sci-fi setting. The juxtaposition of advanced technology with a mundane, frustrating real-world warning symbol creates instant comedic contrast. This image is perfect for memes about overcomplicating simple problems, tech diagnostics gone wrong, or mock-serious overanalysis of basic issues.',
                'image_path' => '/assets/img/95.png',
                'category' => 'clásico'
            ],
            [
                'title' => '102',
                'description' => 'A still from *Star Trek: The Next Generation* showing two crew members in yellow uniforms, Data and Geordi La Forge, intently examining a large futuristic monitorA surreal and highly cursed image of a man giving a thumbs-up, but his entire head has been morphed into a giant thumb. The thumbs nail replaces the top of his skull, and his face is unnaturally embedded into the side of the thumb structure. He wears a white shirt and stands against a bland studio-style background, adding to the uncanny vibe. This image is pure meme absurdity — ideal for sarcastic approval, ironic positivity, or “everything is fine” energy taken to grotesque extremes.',
                'image_path' => '/assets/img/102.png',
                'category' => 'clásico'
            ],
            [
                'title' => '103',
                'description' => 'A close-up photo of a hand holding what appears to be a rolled joint, but upon closer inspection, the wrap is actually a piece of baby corn. The image humorously blends the visual language of smoking culture with food imagery, creating a surreal and absurd visual pun. A lighter is also in frame, ready to ignite the corn blunt. This is prime meme material for themes like when youre broke but resourceful, farm-to-table hits different, or just peak stoner absurdity.',
                'image_path' => '/assets/img/103.png',
                'category' => 'clásico'
            ],
            [
                'title' => '138',
                'description' => 'A bizarre and perfectly executed visual gag showing a driveway with multiple identical red PT Cruiser-style cars — and each one parked next to or inside a proportionally scaled-down house. Theres a normal-sized house at the end of the driveway, then a medium house with a slightly smaller car, followed by even smaller houses and cars as you go down the driveway. The effect makes it look like the cars and houses are part of some generational lineage or toy-like nesting structure. It’s ideal for absurdist memes, jokes about family resemblance, car cults, simulation glitches, or reality slowly breaking down.',
                'image_path' => '/assets/img/138.png',
                'category' => 'clásico'
            ],
            [
                'title' => '144',
                'description' => 'A crudely drawn, deliberately distorted parody of the Mona Lisa, styled with chaotic brush strokes and exaggerated proportions. The face has been replaced by a grotesque meme-like character featuring an asymmetrical smile, hollow eyes, and a large blue tear running down the cheek. The background loosely mimics Da Vinci’s original landscape but with surreal, sketchy textures. This image embodies peak internet irony — a blend of classic art reference and low-effort absurdity, ideal for memes mocking sophistication, emotional breakdowns, or “cursed” aesthetic humor.',
                'image_path' => '/assets/img/144.png',
                'category' => 'clásico'
            ],
            [
                'title' => '155',
                'description' => 'A distorted and grotesque edit of Patrick Star from *SpongeBob SquarePants*. His face has been unnaturally stretched into a tall, cone-like shape with a hollowed-out center, resembling a burnt funnel or a collapsed black hole. The body and setting remain true to the cartoon’s style, but the surreal facial deformation gives the image a cursed, nightmarish quality. Its the kind of visual used for deeply ironic or absurd humor — ideal for memes about existential dread, glitching reality, or brain-melting confusion.',
                'image_path' => '/assets/img/155.png',
                'category' => 'clásico'
            ],
            [
                'title' => '158',
                'description' => 'A heavily edited meme image of a sperm cell, transformed into a parody of hypermasculine internet tropes. The sperm has muscular human arms flexing on both sides, a gold watch, pixelated “deal with it” sunglasses, and a blunt in its mouth. Set against a black background, the absurdity is maximized by combining fertility imagery with stereotypical “alpha” aesthetics. This is peak ironic meme material — great for jokes about unstoppable confidence, genetic superiority, or exaggerated ego from conception.',
                'image_path' => '/assets/img/158.png',
                'category' => 'clásico'
            ],
            [
                'title' => '170',
                'description' => 'A two-panel rage comic-style meme with an orange-red color scheme. In the first panel, a serious man glares out his window, where a massive nuclear explosion is taking place. He looks frustrated, running his hand through his hair. In the second panel, he calmly closes the curtains and returns to his computer, ignoring the apocalypse outside. His expression changes to passive acceptance. This meme format is used to represent ignoring chaos, disaster, or overwhelming problems in favor of staying online, gaming, scrolling, or avoiding reality — often to darkly comedic or nihilistic effect.',
                'image_path' => '/assets/img/170.png',
                'category' => 'clásico'
            ],
            [
                'title' => '194',
                'description' => 'A minimalist, crudely drawn meme figure (similar to Wojak style) with eyes closed and an expression of serene detachment while pinching the bridge of its nose in a gesture of mental exhaustion or forced patience. The background is teal, and the character exudes a calm but defeated energy — as if trying to remain composed while enduring something painfully dumb or frustrating. It’s widely used in memes to convey *“I’m too tired for this,”* *“I’m done explaining,”* or *“inner peace despite outer nonsense.”*',
                'image_path' => '/assets/img/194.png',
                'category' => 'clásico'
            ],
            [
                'title' => '210',
                'description' => 'A classic meme image of the “thinking emoji” edited to mimic the iconic “Roll Safe” gesture — smirking and tapping its temple with one finger, suggesting cleverness or a smug realization. This image is used to mock superficial logic, flawed reasoning disguised as genius, or to ironically justify bad decisions with faux-smart logic. It’s perfect for sarcastic takes on “you can’t fail if you don’t try”–style wisdom.',
                'image_path' => '/assets/img/210.png',
                'category' => 'clásico'
            ],
            [
                'title' => '233',
                'description' => 'A surreal and darkly humorous image showing a masked militant — wearing a black balaclava and ammo belts — calmly sipping tea from a delicate porcelain cup. The contrast between the expected aggression of the outfit and the dainty, civilized act of tea-drinking creates instant meme tension. It’s perfect for sarcastic or ironic captions about staying unbothered, casually handling chaos, or maintaining poise in wildly inappropriate contexts. Peak *“calm under fire”* meme energy.',
                'image_path' => '/assets/img/233.png',
                'category' => 'clásico'
            ],
            [
                'title' => '233',
                'description' => 'A surreal and darkly humorous image showing a masked militant — wearing a black balaclava and ammo belts — calmly sipping tea from a delicate porcelain cup. The contrast between the expected aggression of the outfit and the dainty, civilized act of tea-drinking creates instant meme tension. It’s perfect for sarcastic or ironic captions about staying unbothered, casually handling chaos, or maintaining poise in wildly inappropriate contexts. Peak *“calm under fire”* meme energy.',
                'image_path' => '/assets/img/233.png',
                'category' => 'clásico'
            ],
            [
                'title' => '244',
                'description' => 'A rage-core meme image featuring a man screaming, overlaid with an intense red filter and glowing laser eyes — a visual language used to amplify raw emotion, usually extreme anger, hype, or unhinged energy. This format is commonly used to depict overreactions, primal fury, or ironic escalation of minor inconveniences into full nuclear meltdown. Its peak * “triggered beyond reason” * meme material .',
                'image_path' => '/assets/img/244.png',
                'category' => 'clásico'
            ],
            [
                'title' => '252',
                'description' => 'A wholesome and peaceful image of Pepe the Frog wearing a white bathrobe, calmly enjoying a warm breakfast in a sunlit, cozy kitchen. He’s holding a cup of coffee with a gentle smile, surrounded by potted plants, a “Good Morning!” sign, and a neatly set table with eggs, toast, and more coffee. The soft lighting and relaxed vibe make this meme perfect for expressing serenity, gratitude, or *“life is good”* energy — often used either genuinely or with soft irony for contrast in online discourse.',
                'image_path' => '/assets/img/252.png',
                'category' => 'clásico'
            ],
            [
                'title' => '265',
                'description' => 'A classic cartoon image of Daffy Duck sitting at a table surrounded by large stacks of cash, gleefully holding bundles of money in both hands. His wide, unhinged grin and manic energy give off pure greed and self-indulgence. The background is a dreamy, exaggerated purple with chaotic swirls, enhancing the surreal vibe. This frame is ideal for memes about sudden wealth, shameless hustle culture, crypto pumps, or anyone flexing like they just scammed their way into generational riches.',
                'image_path' => '/assets/img/265.png',
                'category' => 'clásico'
            ],
            [
                'title' => '327',
                'description' => 'A bizarre, DIY-looking car parked in a lot, completely covered in green corrugated panels that make it resemble a house or shed on wheels. It has blacked-out wheels and a makeshift, angular structure, giving it an absurd, almost post-apocalyptic or meme-worthy appearance. The background shows a fast food place (Arby’s) and some dumpsters, enhancing the surreal setting.',
                'image_path' => '/assets/img/327.png',
                'category' => 'clásico'
            ],
            [
                'title' => '340',
                'description' => 'Patrick Star from SpongeBob is shown with his arms crossed, sweating and looking intensely focused or angry. His brows are furrowed and he’s visibly stressed, possibly trying to hold himself back or stay calm in a ridiculous situation. Classic meme expression of forced restraint or internal struggle.',
                'image_path' => '/assets/img/340.png',
                'category' => 'clásico'
            ],
            [
                'title' => '352',
                'description' => 'Two intense characters stand in a dramatic glow, reaching out with open hands toward each other—not to fight, but to shake hands. Despite the explosive energy and confrontational stance, it s a moment of unexpected mutual respect forged in chaos .',
                'image_path' => '/assets/img/352.png',
                'category' => 'clásico'
            ],

        ];




        foreach ($memes as $meme) {
            \App\Models\Meme::create($meme);
        }

        response()->json(['message' => 'Memes cargados desde array', 'count' => count($memes)]);
    }

    public function generateEmbeddings()
    {
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]
        ]);

        $memes = Meme::whereNull('embedding')->get();

        foreach ($memes as $meme) {
            $description = $meme->description;

            $response = $client->post('embeddings', [
                'verify' => false,
                'json' => [
                    'model' => 'text-embedding-3-small',
                    'input' => $description
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $embedding = $data['data'][0]['embedding'];

            $meme->embedding = json_encode($embedding);
            $meme->save();
        }

        response()->json(['message' => 'Embeddings generated', 'count' => count($memes)]);
    }

    public function matchPrompt()
    {
        $prompt = request()->get('prompt');

        if (!$prompt) {
            response()->json(['error' => 'No prompt provided'], 400);
            return;
        }

        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]
        ]);

        $response = $client->post('embeddings', [
            'verify' => false,
            'json' => [
                'model' => 'text-embedding-3-small',
                'input' => $prompt
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $promptEmbedding = $data['data'][0]['embedding'];

        $bestScore = -1;
        $bestMeme = null;

        foreach (Meme::all() as $meme) {
            if (!$meme->embedding) continue;

            $memeEmbedding = json_decode($meme->embedding);
            $similarity = $this->cosineSimilarity($promptEmbedding, $memeEmbedding);

            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMeme = $meme;
            }
        }

        if (!$bestMeme) {
            response()->json(['error' => 'No memes with embeddings found'], 404);
            return;
        }

        $caption = $this->generateCaption($prompt, $bestMeme->description);

        response()->json([
            'message' => 'Best matching meme found',
            'meme' => $bestMeme,
            'caption' => $caption,
            'score' => $bestScore
        ]);
    }

    private function cosineSimilarity($a, $b)
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($a); $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    private function generateCaption($prompt, $memeDescription)
    {
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]
        ]);

        $systemPrompt = "You are an extremely online meme lord. Your job is to write short, absurd, dank, or darkly sarcastic captions for memes. Think in the style of deep-fried memes, shitpost. Make them clever, and funny and roasty. No explanation. Just the caption. Don't add any text formatting like bold or italic, nor add emojis or hashtags.";

        $userMessage = "Prompt: \"$prompt\"\nMeme Description: \"$memeDescription\"";

        $response = $client->post('chat/completions', [
            'verify' => false,
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 60
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? null;
    }

    public function generateFinalMeme()
    {
        $prompt = request()->get('prompt');

        if (!$prompt) {
            response()->json(['error' => 'No prompt provided'], 400);
            return;
        }

        $embedding = $this->getPromptEmbedding($prompt);

        $bestScore = -1;
        $bestMeme = null;

        foreach (Meme::all() as $meme) {
            if (!$meme->embedding) continue;

            $similarity = $this->cosineSimilarity($embedding, json_decode($meme->embedding));
            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMeme = $meme;
            }
        }

        if (!$bestMeme) {
            response()->json(['error' => 'No matching meme'], 404);
            return;
        }

        $caption = $this->generateCaption($prompt, $bestMeme->description);

        $imagePath = __DIR__ . '/../../public' . $bestMeme->image_path;
        $outputPath = __DIR__ . '/../../public/generated/final_' . uniqid() . '.png';

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $original = $manager->read($imagePath);

        $fontPath = __DIR__ . '/../fonts/Anton-Regular.ttf';
        $fontSize = max(20, min(intval($original->height() * 0.06), 60));
        $maxWidth = intval($original->width() * 1);

        function wrapText($text, $fontPath, $fontSize, $maxWidth)
        {
            $words = explode(' ', $text);
            $lines = [];
            $currentLine = '';

            foreach ($words as $word) {
                $testLine = trim($currentLine . ' ' . $word);
                $box = imagettfbbox($fontSize, 0, $fontPath, $testLine);
                $lineWidth = abs($box[2] - $box[0]);

                if ($lineWidth < $maxWidth) {
                    $currentLine = $testLine;
                } else {
                    if ($currentLine) $lines[] = $currentLine;
                    $currentLine = $word;
                }
            }
            if ($currentLine) $lines[] = $currentLine;
            return $lines;
        }

        $lines = wrapText($caption, $fontPath, $fontSize, $maxWidth);
        $lineHeight = $fontSize + 10;
        $paddingTop = 20;
        $paddingBottom = 20;
        $textAreaHeight = count($lines) * $lineHeight + $paddingTop + $paddingBottom;

        $canvas = $manager->create($original->width(), $original->height() + $textAreaHeight);
        $canvas->fill('#ffffff');
        $canvas->place($original, 'top-left', 0, $textAreaHeight);

        foreach ($lines as $i => $line) {
            $y = $paddingTop + $i * $lineHeight;

            $canvas->text($line, $canvas->width() / 2, $y, function ($font) use ($fontPath, $fontSize) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        $canvas->save($outputPath);

        Caption::create([
            'meme_id' => $bestMeme->id,
            'caption' => $caption
        ]);


        response()->json([
            'image_url' => '/generated/' . basename($outputPath),
            'caption' => $caption,
            'meme_id' => $bestMeme->id,
            'score' => $bestScore
        ]);
    }


    public function showMeme($id)
    {
        $meme = Meme::findOrFail($id);

        $perPage = 12;
        $currentPage = request()->get('page', 1);

        $allCaptions = $meme->captions()->get()->toArray();
        $total = count($allCaptions);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allCaptions, $offset, $perPage);

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => "/meme/$id",
                'query' => request()->query(),
            ]
        );

        return render('meme', [
            'meme' => $meme,
            'captions' => $paginator,
        ]);
    }


    private function getPromptEmbedding($prompt)
    {
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]
        ]);

        $response = $client->post('embeddings', [
            'verify' => false,
            'json' => [
                'model' => 'text-embedding-3-small',
                'input' => $prompt
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['data'][0]['embedding'];
    }
}
