<div class="w-[90%] max-w-[1200px] mx-auto flex flex-col px-[5%] py-5">
    <input wire:model.live="search" type="text"
        class="w-full h-12 px-2 py-1 mt-1 mb-3 text-lg bg-gray-400 border-0 rounded placeholder:text-gray-800"
        placeholder="Search">
    @foreach ($users as $user)
        <div wire:click="createNewChat({{ $user->id }})"
            class="flex items-center justify-start w-full p-3 text-gray-300 transition-all duration-300 ease-in-out cursor-pointer hover:bg-gray-700 hover:text-gray-100">
            <div class="mr-3 w-fit">
                <img width="32" height="32" class="object-cover overflow-hidden rounded-full shadow-inner"
                    src="john.jpg" alt="">
            </div>
            <div>
                <h2 class="font-semibold">{{ $user->name }}</h2>
                <p class="text-xs">{{ $user->email }}</p>
            </div>
        </div>
    @endforeach
    <div class="m-4">

        {{ $users->links() }}
    </div>


</div>
