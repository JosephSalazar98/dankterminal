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

    @php
        $current = request()->getPathInfo();
    @endphp

    <div
        class="text-green-400 px-4 py-2 font-mono flex flex-col gap-2 text-xs md:text-sm shadow-inner border border-green-600 w-full max-w-4xl mx-auto mb-4">
        <a href="/" class="hover:underline {{ $current === '/' ? 'text-white' : 'text-green-400' }} mr-4">home</a>
        <a href="/gallery"
            class="hover:underline {{ $current === '/gallery' ? 'text-white' : 'text-green-400' }} mr-4">gallery</a>
        <a href="https://x.com/Eserya77" target="_blank" class="hover:underline mr-4">twitter</a>
        <span class="break-all">donate: 5eJHrn46JCNrk5gPRCqjwCipMn6rxnVc5aFzrMC4cH39</span>
    </div>



</div>
