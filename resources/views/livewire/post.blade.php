<?php

use Livewire\Volt\Component;
use \App\Models\article;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads; // para makapag upload ng file


    public $content;
    public $image;

    public function submit()
    {
        $this->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('images', 'public');
        }


        article::create([
            'user_id' => auth()->id(),
            'postText' => $this->content,
            'postImage' => $imagePath
        ]);

        $this->reset('content', 'image');
        return redirect(request()->header('Referer'));
    }
}; ?>

<div>
    <form wire:submit.prevent="submit">
        <div class="mb-8 bg-slate-600 rounded-lg shadow p-4 border border-transparent">
        <div class="flex items-start space-x-3">
            <img src="{{ auth()->user()->profile_picture }}" alt="Profile" class="w-10 h-10 rounded-full">
            <input
                wire:model.defer="content"
                class="w-full bg-[#3A3B3C] border-none rounded-full p-3 text-white placeholder-gray-400 focus:ring-0"
                placeholder="What's on your mind, {{ auth()->user()->name }}?"
            />
        </div>
        <hr class="my-3 border-[#3A3B3C]">
        <div class="flex flex-row">
            <div class="flex justify-between">
                <button type="button" class="flex items-center space-x-2 text-pink-500 hover:bg-[#3A3B3C] px-4 py-2 rounded transition relative">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M15 8a3 3 0 11-6 0 3 3 0 016 0z"></path><path fill-rule="evenodd" d="M2 13.5A4.5 4.5 0 016.5 9h7a4.5 4.5 0 014.5 4.5V17a1 1 0 01-1 1H3a1 1 0 01-1-1v-3.5z" clip-rule="evenodd"></path></svg>
                <input
                    type="file"
                    class="absolute inset-0 opacity-0 cursor-pointer"
                    wire:model="live_video"
                >
                <span>Live video</span>
                </button>
            </div>
            <div class="relative">
                <button class="flex items-center space-x-2 text-green-500 hover:bg-[#3A3B3C] px-4 py-2 rounded transition relative">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5zm6 2a3 3 0 100 6 3 3 0 000-6z"></path></svg>
                    <input
                        type="file"
                        class="absolute inset-0 opacity-0 cursor-pointer"
                        wire:model="image"
                    >
                    <span>Photo/video</span>
                </button>
            </div>
            <button type="submit" class="btn btn-primary ml-auto">Submit</button>
        </div>

        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    </form>
</div>
