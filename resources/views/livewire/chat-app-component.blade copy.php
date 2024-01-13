<div class="w-full overflow-hidden   xl:w-[95%] max-w-[1400px] flex flex-wrap flex-col shadow-xl-white mx-auto p-0 rounded h-[600px] max-h-[600px] mt-12 bg-gray-800">


    {{-- Chat List Section --}}
    <div class="w-[100%]  max-w-[100%]   overflow-y-auto  lg:block my-0 p-0 border-r items-center flex flex-col border-gray-700 h-[600px]" id="chatListContainer">
        <div class="h-[60px] sticky top-0  flex items-center m-0 p-0 w-full bg-slate-800 border-b border-gray-700">
            <div class="flex items-center w-full p-3 border-gray-700">
                <div class="mr-3 w-fit">
                    <img width="32" height="32" class="object-cover overflow-hidden rounded-full" src="john.jpg" alt="">
                </div>
                <div>
                    <h2 class="font-semibold text-gray-300">{{ auth()->user()->name }}</h2>
                </div>
            </div>
        </div>
        @foreach ($chats as $chat)
        <div wire:click="selectChat({{ $chat->id }})" id="chatItem" class="relative flex items-center justify-center w-full h-20 px-3 text-gray-300 transition-all duration-300 ease-in-out cursor-pointer chat-item hover:bg-gray-600 hover:text-gray-100 bg-secondary ">
            <div class="w-auto py-3 mr-3 ">
                <img width="50" height="50" class="object-cover overflow-hidden rounded-full shadow-inner" src="john.jpg" alt="">
            </div>
            <div class="w-full py-3 border-b border-gray-600 ">
                <h2 class="font-semibold">{{ $chat->name }}</h2>
                <p class="text-xs">
                    @if (isset($chat->last_message_id))
                    {{ Illuminate\Support\Str::limit($chat->lastMessage->message, 35) }}
                    <span class="absolute text-sm text-gray-200 top-3 right-3">
                        {{ $chat->lastMessage->created_at->format('H:i') }}
                    </span>
                    @endif
                </p>
            </div>
        </div>
        @endforeach


    </div>
    <div id="rightSection" class="flex w-full items-center lg:max-w-[75%] justify-center h-100 text-2xl text-center text-gray-200">
        @if (isset($activeChat))
        {{-- Active Chat Section --}}
        <div class="w-full my-0 p-0 h-[600px] text-md" id="chatContainer">
            <div class="h-[60px] m-0 p-0 w-full border-b border-gray-700 flex items-center px-4">
                <div class="flex items-center transition-all duration-300 ease-in-out rounded-full ">
                    <div class="p-2 transition-all duration-500 ease-in-out rounded-full cursor-pointer hover:bg-gray-600" id="closeActiveChat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-white bi bi-arrow-left " viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-center ml-4">
                        <img width="32" height="32" class="object-cover overflow-hidden rounded-full shadow-inner" src="john.jpg" alt="">
                        <div class="ml-3">
                            <h2 class="text-base font-semibold text-white ">
                                {{ $activeChat->users->where('id', '!=', auth()->id())->first()->name }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-b pt-2 h-[480px] border-gray-700 overflow-x-auto" id="messagesContainer">
                @foreach ($messages as $key => $message)
                <div wire:key="{{ $message->id }}" class="flex p-2 py-1 relative @if ($key === count($messages) - 1 || $message->user_id !== $messages[$key + 1]->user_id) mb-6 @endif my-[2px] w-fit rounded max-w-[80%] mx-[2%] @if ($message->user_id == Auth::id()) ml-auto bg-gray-500 @else bg-cyan-900 bg-primary @endif">
                    <p class="px-4 py-1 text-sm text-gray-200">
                        {{ $message->message }}
                    </p>
                    @if ($key === count($messages) - 1 || $message->user_id !== $messages[$key + 1]->user_id)
                    <span class="absolute text-xs text-gray-400 bottom-[-20px] left-0">
                        {{ $message->created_at->format('H : i') }}
                    </span>
                    @endif
                </div>
                @endforeach


            </div>


            <form wire:submit.prevent="sendMessage">
                <div class="h-[60px] w-full flex px-4 items-center justify-between">
                    <input required type="text" wire:model="message" placeholder="Write a message" class="w-full mx-2 my-0 text-black bg-gray-400 border-none outline-none placeholder:text-gray-700 rounded-xl opacity-70">
                    <button type="submit" class="items-center justify-center p-3 mx-2 my-1 transition-all duration-500 ease-in-out rounded-lg hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-xl text-gray-300 bi bi-send" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>

        </div>
        @else
        <div class="flex w-full xl:w-[95%] max-w-[1400px] items-center justify-center text-2xl text-center text-gray-200">
            <h1 class="block mx-auto">Select a Conversation</h1>
        </div>
        @endif
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    setTimeout(() => {
        var windowWidth = window.innerWidth;
        if (windowWidth < 1024) {
            document.getElementById("rightSection").innerHTML = "";

            document.getElementById("rightSection").style.display = 'none';
            document.getElementById("chatListContainer").style.display = 'flex';

        } else {
            document.getElementById("rightSection").innerHTML = "Select A Conversation";
        }

    }, 20);
    document.addEventListener('livewire:initialized', () => {
        var activeChat = false;
        var scrollListenerAdded = false;
        var scrollHeight = 0;

        function addScrollListener() {
            console.log('Scroll dinleyicisi eklendi');
            $("#messagesContainer").on('scroll', function() {
                var container = this;
                var top = $('#messagesContainer').scrollTop();

                // console.log('scroll:' + top);
                if (top == 0) {
                    scrollHeight = container.scrollHeight;
                    console.log(scrollHeight);
                    @this.dispatch('loadMoreMessages');

                }
            });
            scrollListenerAdded = true;
        }

        function checkActiveChat() {
            var chat = document.getElementById('messagesContainer');

            if (chat != null) {
                activeChat = true;
                if (!scrollListenerAdded) {
                    addScrollListener();
                }
            } else {
                activeChat = false;
                if (scrollListenerAdded) {
                    $("#messagesContainer").off('scroll');
                    scrollListenerAdded = false;
                }
            }
        }
        setInterval(checkActiveChat, 1000);
        const scrollSmoothlyToBottom = (id) => {
            const element = $(`#${id}`);
            element.animate({
                    scrollTop: element.prop("scrollHeight"),
                },
                1120
            );
        };

        window.Livewire.hook('chat-app-component.init', ({
            component,
            cleanup
        }) => {
            console.log(24)
        })
        console.log('livewire initialized')
        @this.on('messageReceived', (event) => {
            scrollSmoothlyToBottom('messagesContainer');
            console.log(event);
        });

        @this.on('selectChat', (event) => {
            console.log("sohbet açıldı");
            setTimeout(() => {
                var windowWidth = window.innerWidth;
                if (windowWidth < 1024) {
                    document.getElementById("rightSection").style.display = 'flex';
                    document.getElementById("chatListContainer").style.display = 'none';
                }

            }, 5);
        });

        @this.on('messagesAdded', (event) => {
            setTimeout(() => {
                var container = document.getElementById('messagesContainer');
                container.scrollTop = container.scrollHeight - (scrollHeight - 90);
            }, 10);
        });

    });

    $(document).on("click", "#closeActiveChat", function() {
        console.log("sohbet kapandı");
        var windowWidth = window.innerWidth;
        if (windowWidth < 1024) {
            document.getElementById("rightSection").innerHTML = "";

            document.getElementById("rightSection").style.display = 'none';
            document.getElementById("chatListContainer").style.display = 'flex';

        } else {
            document.getElementById("rightSection").innerHTML = "Select A Conversation";
        }
    });



    function adjustChatView() {
        var windowWidth = window.innerWidth;
        if (windowWidth < 1024) {
            var acviteChat = document.getElementById("chatContainer");
            if (acviteChat == null) {
                document.getElementById("rightSection").style.display = "none";
            } else {
                console.log("sohbet var");
            }
        }
    }

    // Event: Pencere boyutu değiştiğinde
    $(window).resize(function() {
        adjustChatView();
    });
</script>