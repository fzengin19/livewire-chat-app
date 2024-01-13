<div class="w-full my-0 p-0 h-[600px] text-md" id="chatContainer">
    <div class="h-[60px] m-0 p-0 w-full border-b border-gray-700 flex items-center px-4">
        <div class="flex items-center transition-all duration-300 ease-in-out rounded-full ">
            <div class="p-2 transition-all duration-500 ease-in-out rounded-full cursor-pointer hover:bg-gray-600"
                id="closeActiveChat" wire:click="closeChat()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="text-white bi bi-arrow-left " viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
            </div>
            <div class="flex items-center justify-center ml-4">
                <img width="32" height="32" class="object-cover overflow-hidden rounded-full shadow-inner"
                    src="john.jpg" alt="">
                <div class="ml-3">
                    <h2 class="text-base font-semibold text-white ">
                        {{ $activeChat->users->where('id', '!=', auth()->id())->first()->name }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="border-b pt-2 h-[480px] border-gray-700 overflow-x-auto" id="messagesContainer">
        @foreach ($messages as $key => $message)
            <div wire:key="{{ $message->id }}"
                class="flex p-2 py-1 relative @if ($key === 0 || (isset($messages[$key - 1]) && $message->user_id != $messages[$key - 1]->user_id)) mb-6 @endif my-[2px] w-fit rounded max-w-[80%] mx-[2%] @if ($message->user_id == Auth::id()) ml-auto bg-gray-500 @else bg-cyan-900 bg-primary @endif">
                <p class="px-4 py-1 text-sm text-gray-200">
                    {{ $message->message }}
                </p>
                @if ($key === 0 || (isset($messages[$key - 1]) && $message->user_id != $messages[$key - 1]->user_id))
                    <span class="absolute text-xs text-gray-400 bottom-[-20px] left-0">
                        {{ $message->created_at->format('H : i') }}
                    </span>
                @endif
            </div>
        @endforeach


    </div>


    <form wire:submit.prevent="sendMessage">
        <div class="h-[60px] w-full flex px-4 items-center justify-between">
            <input required type="text" wire:model="message" placeholder="Write a message"
                class="w-full mx-2 my-0 text-black bg-gray-400 border-none outline-none placeholder:text-gray-700 rounded-xl opacity-70">
            <button type="submit"
                class="items-center justify-center p-3 mx-2 my-1 transition-all duration-500 ease-in-out rounded-lg hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="text-xl text-gray-300 bi bi-send" viewBox="0 0 16 16">
                    <path
                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z">
                    </path>
                </svg>
            </button>
        </div>
    </form>

</div>
