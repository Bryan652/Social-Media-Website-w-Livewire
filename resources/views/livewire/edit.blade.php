<?php

use Livewire\Volt\Component;

new class extends Component {
    public $article;
    public $userArticle;

    public function mount() {
        $this->userArticle = auth()->user()->article()->with('user')->latest()->get();
    }
}; ?>

<div>
    <h1 class="mb-8"> User Posts</h1>
    <div class="rounded-md">
        @foreach ($userArticle as $item)
            <div class="mb-8 bg-slate-600 rounded-lg shadow p-4">
                <div class="flex items-center mb-3">
                    <x-mary-avatar :image="$item->user->profile_picture" :title="$item->user->name" class="!w-12 !h-12" />
                    <a class="ml-auto" href="{{ route('profile', $item->id) }}">
                        <x-mary-button label="Edit" class="btn-primary" />
                    </a>
                </div>
                <div class="text-white mb-3">{{ $item->postText }}</div>

                <!-- Check kung meron image -->
                @if($item->postImage)
                    <div class="mb-3 relative">
                        <!-- gagawa yung alpinejs ng local state loaded initial value = false-->
                        <div x-data="{ loaded: false }">
                            <!-- pag di pa nagloload papakita tong block -->
                            <template x-if="!loaded">
                                <x-mary-loading class="inset-0 z-10" />
                            </template>
                            <!-- Hidden to hanggang matapos mag load yung pic-->
                            <img
                                src="{{ $item->postImage }}"
                                alt=""
                                class="rounded-lg max-h-96 w-full object-cover"
                                @load="loaded = true"
                            >
                        </div>
                    </div>
                @endif
                <div class="flex items-center text-gray-500 text-sm border-t pt-2 space-x-6">
                    <button class="hover:text-blue-600 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 9V5a3 3 0 00-6 0v4M5 15h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                        <span>Like</span>
                    </button>
                    <button class="hover:text-blue-600 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8l-4 1 1-4A8.96 8.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span>Comment</span>
                    </button>
                    <button class="hover:text-blue-600 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path></svg>
                        <span>Share</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
