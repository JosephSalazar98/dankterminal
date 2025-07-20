<!DOCTYPE html>
<html lang="en" x-data="memeGenerator()">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dank Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="icon" type="image/png" href="/fav.png">

</head>

<body class="bg-black text-green-400 font-mono min-h-screen p-2 md:p-12">
    <div class="max-w-full md:max-w-3xl mx-auto items-center content-center" x-data="memeGenerator()">
        <div class="mb-6">
            <pre class="text-green-500 md:text-[0.5rem] text-[0.25rem] mb-4 md:block ">                                        

                                                                                                                         
      ##### ##                             /                #####   ##    ##                                             
   /#####  /##                           #/              ######  /#### #####                                             
 //    /  / ###                          ##             /#   /  /  ##### #####                                           
/     /  /   ###                         ##            /    /  /   # ##  # ##                                            
     /  /     ###                        ##                /  /    #     #                                               
    ## ##      ##    /###   ###  /###    ##  /##          ## ##    #     #      /##  ### /### /###     /##       /###    
    ## ##      ##   / ###  / ###/ #### / ## / ###         ## ##    #     #     / ###  ##/ ###/ /##  / / ###     / #### / 
    ## ##      ##  /   ###/   ##   ###/  ##/   /          ## ##    #     #    /   ###  ##  ###/ ###/ /   ###   ##  ###/  
    ## ##      ## ##    ##    ##    ##   ##   /           ## ##    #     #   ##    ### ##   ##   ## ##    ### ####       
    ## ##      ## ##    ##    ##    ##   ##  /            ## ##    #     ##  ########  ##   ##   ## ########    ###      
    #  ##      ## ##    ##    ##    ##   ## ##            #  ##    #     ##  #######   ##   ##   ## #######       ###    
       /       /  ##    ##    ##    ##   ######              /     #      ## ##        ##   ##   ## ##              ###  
  /###/       /   ##    /#    ##    ##   ##  ###         /##/      #      ## ####    / ##   ##   ## ####    /  /###  ##  
 /   ########/     ####/ ##   ###   ###  ##   ### /     /  #####           ## ######/  ###  ###  ### ######/  / #### /   
/       ####        ###   ##   ###   ###  ##   ##/     /     ##                #####    ###  ###  ### #####      ###/    
#                                                      #                                                                 
 ##                                                     ##                                                               
                                                                                                                         
                                                                                                                         
                                   
            </pre>


        </div>


        <template x-for="(line, index) in history" :key="index">
            <div class="mb-2">
                <p class="flex flex-wrap break-words whitespace-pre-wrap">
                    <span class="text-green-500 mr-1">user@dankmemes:~$</span>
                    <span x-text="line.command"></span>
                </p>


                <template x-if="line.spinner">
                    <p class="ml-4 text-yellow-400" x-text="'Generating... ' + line.spinner"></p>
                </template>

                <template x-if="line.reply">
                    <p class="ml-4 text-green-300" x-html="line.reply"></p>
                </template>


                <template x-if="line.caption">
                    <p class="ml-4 text-green-300">→ <span x-text="line.caption"></span></p>
                </template>

                <template x-if="line.image">
                    <img :src="line.image" class="ml-4 mt-2 border border-green-600 max-w-full max-h-[600px]" />
                </template>

                <template x-if="line.error">
                    <p class="ml-4 text-red-400" x-text="line.error"></p>
                </template>
            </div>
        </template>

        <!-- INPUT PROMPT, hidden while loading -->
        <div class="flex flex-col sm:flex-row items-start mt-4 gap-2" x-show="!loading">
            <span class="text-green-500">user@dankmemes:~$</span>
            <textarea x-model="prompt" @keydown.enter.prevent="generateMeme" @input="resize($event.target)"
                class="w-full bg-black border-none focus:outline-none placeholder-green-600 text-white resize-none"
                placeholder="Type a command like /generate or /help" rows="1" style="overflow: hidden;"></textarea>
        </div>

    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("memeGenerator", () => ({
                prompt: "",
                history: [],
                loading: false,
                spinnerInterval: null,
                spinnerFrames: ["/", "-", "\\", "|"],
                spinnerIndex: 0,
                resize(el) {
                    el.style.height = "auto";
                    el.style.height = (el.scrollHeight) + "px";
                },

                async generateMeme() {
                    const input = this.prompt.trim();
                    this.prompt = "";

                    if (!input.startsWith("/")) {
                        this.history.push({
                            command: input,
                            error: "Commands must start with /"
                        });
                        return;
                    }

                    const [cmd, ...args] = input.slice(1).split(" ");
                    const argText = args.join(" ");
                    const entry = {
                        command: input
                    };
                    this.history.push(entry);
                    const index = this.history.length - 1;

                    if (cmd === "generate") {
                        this.loading = true;
                        this.history[index].spinner = this.spinnerFrames[0];
                        this.spinnerIndex = 0;

                        this.spinnerInterval = setInterval(() => {
                            this.spinnerIndex = (this.spinnerIndex + 1) % this.spinnerFrames
                                .length;
                            this.history[index].spinner = this.spinnerFrames[this
                                .spinnerIndex];
                        }, 100);

                        try {
                            const res = await fetch("/memes/generate", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    prompt: argText
                                }),
                            });

                            const data = await res.json();
                            clearInterval(this.spinnerInterval);
                            delete this.history[index].spinner;
                            this.loading = false;

                            if (data.image_url) {
                                this.history[index].caption = data.caption;
                                this.history[index].image = data.image_url;
                            } else {
                                this.history[index].error = data.error || "Something went wrong.";
                            }
                        } catch (e) {
                            clearInterval(this.spinnerInterval);
                            delete this.history[index].spinner;
                            this.loading = false;
                            this.history[index].error = "Failed to reach server.";
                        }

                        return;

                    } else if (cmd === "ca") {
                        this.history[index].reply = "GPwwihLa1w9Qsz27SmNrj7aLVnE7pr2cAvHe6aVVpump";

                    } else if (cmd === "x") {
                        this.history[index].reply = "https://x.com/DANKMEMESCTO333";

                    } else if (cmd === "website") {
                        this.history[index].reply = "https://www.dankmemesonsol.net/";

                    } else if (cmd === "dex") {
                        this.history[index].reply =
                            "https://dexscreener.com/solana/4d1ldienf5rktjivgbvfbbuw4kpcnpyprpnnjk1at8z2";

                    } else if (cmd === "help") {
                        this.history[index].reply = `<pre>
Available commands:
/generate [prompt]   
/ca                  
/x                   
/website              
/dex                  
/help                
</pre>`;




                    } else {
                        this.history[index].error = `Unknown command: /${cmd}`;
                    }
                }
            }));
        });
    </script>
</body>

</html>
